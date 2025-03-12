<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator;

use Dokky\ClassSchemaGenerator\PropertyContextReader\AttributePropertyContextReader;
use Dokky\ClassSchemaGenerator\PropertyContextReader\ChainPropertyContextReader;
use Dokky\ClassSchemaGenerator\PropertyContextReader\SymfonyPropertyContextReader;
use Dokky\ClassSchemaGeneratorInterface;
use Dokky\ComponentsRegistry;
use Dokky\OpenApi\Schema;
use Dokky\Undefined;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpStanExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

readonly class ClassSchemaGenerator implements ClassSchemaGeneratorInterface
{
    private PropertyInfoExtractorInterface $propertyInfoExtractor;
    private ComponentsRegistry $componentsRegistry;
    private PropertyContextReaderInterface $propertyContextReader;

    public function __construct(
        ?PropertyInfoExtractorInterface $propertyInfoExtractor = null,
        ?ComponentsRegistry $componentsRegistry = null,
        ?PropertyContextReaderInterface $propertyContextReader = null,
    ) {
        // TODO: Use cached extractors
        $this->propertyInfoExtractor = $propertyInfoExtractor ?? $this->createPropertyInfoExtractor();
        $this->componentsRegistry = $componentsRegistry ?? new ComponentsRegistry();
        $this->propertyContextReader = $propertyContextReader ?? new ChainPropertyContextReader([
            new AttributePropertyContextReader(),
            new SymfonyPropertyContextReader(),
        ]);
    }

    public function generate(string $className, ?array $groups = null): Schema
    {
        if (enum_exists($className)) {
            return $this->getEnumSchema($className);
        }

        if (null !== $groups) {
            $groups = array_unique([...Util::formatGroups($groups), 'default']);
        }

        $schema = new Schema(
            type: Schema\Type::OBJECT,
            properties: [],
        );
        $required = [];
        $propertyNames = $this->propertyInfoExtractor->getProperties($className);

        if (null === $propertyNames) {
            throw new \RuntimeException(sprintf('No properties found for class "%s"', $className));
        }

        $properties = [];

        foreach ($propertyNames as $propertyName) {
            $reflectionProperty = new \ReflectionProperty($className, $propertyName);
            $propertyContext = $this->propertyContextReader->extract($reflectionProperty);

            // Groups filtering
            if (
                null !== $groups
                && (
                    [] === $propertyContext->groups
                    || 0 === count(array_intersect($propertyContext->groups, $groups))
                )
            ) {
                continue;
            }

            // Ignored properties
            if ($propertyContext->ignored) {
                continue;
            }

            $finalName = $propertyContext->name ?? $propertyName;
            $propertySchema = $this->generatePropertySchema($className, $propertyName, $reflectionProperty);
            $properties[$finalName] = $propertySchema;

            // Constraints
            if (null !== $propertyContext->minLength) {
                $propertySchema->minLength = $propertyContext->minLength;
            }

            if (null !== $propertyContext->maxLength) {
                $propertySchema->maxLength = $propertyContext->maxLength;
            }

            if (null !== $propertyContext->minItems) {
                $propertySchema->minItems = $propertyContext->minItems;
            }

            if (null !== $propertyContext->maxItems) {
                $propertySchema->maxItems = $propertyContext->maxItems;
            }

            if (null !== $propertyContext->minimum) {
                $propertySchema->minimum = $propertyContext->minimum;
            }

            if (null !== $propertyContext->maximum) {
                $propertySchema->maximum = $propertyContext->maximum;
            }

            if (null !== $propertyContext->exclusiveMinimum) {
                $propertySchema->exclusiveMinimum = $propertyContext->exclusiveMinimum;
            }

            if (null !== $propertyContext->exclusiveMaximum) {
                $propertySchema->exclusiveMaximum = $propertyContext->exclusiveMaximum;
            }

            if (Undefined::VALUE === $propertySchema->default) {
                $required[] = $finalName;
            }
        }

        $schema->properties = $properties;

        if (count($required) > 0) {
            $schema->required = $required;
        }

        return $schema;
    }

    private function generatePropertySchema(
        string $className,
        string $propertyName,
        \ReflectionProperty $reflectionProperty,
    ): Schema {
        $foundTypes = $this->propertyInfoExtractor->getTypes($className, $propertyName);

        if (null === $foundTypes) {
            if ($this->isPropertyAllowedOnlyNull($reflectionProperty)) {
                $schema = new Schema(type: Schema\Type::NULL);
            } else {
                throw new \RuntimeException(
                    sprintf('No type found for property "%s" in class "%s"', $propertyName, $className)
                );
            }
        } else {
            $schema = $this->getOpenApiSchemaFromTypes($foundTypes, $reflectionProperty);
        }

        $schema->default = $this->determineDefaultValue($reflectionProperty);

        return $schema;
    }

    /**
     * @param array<Type> $types
     */
    private function getOpenApiSchemaFromTypes(array $types, \ReflectionProperty $reflectionProperty): Schema
    {
        $isNullable = false;
        $schema = new Schema();
        $schemas = [];

        foreach ($types as $type) {
            $isNullable = $isNullable || $type->isNullable();

            switch ($type->getBuiltinType()) {
                case Type::BUILTIN_TYPE_INT:
                    $schemas[] = new Schema(type: Schema\Type::INTEGER);
                    break;
                case Type::BUILTIN_TYPE_FLOAT:
                    $schemas[] = new Schema(type: Schema\Type::NUMBER, format: 'float');
                    break;
                case Type::BUILTIN_TYPE_STRING:
                    $schemas[] = new Schema(type: Schema\Type::STRING);
                    break;
                case Type::BUILTIN_TYPE_BOOL:
                    $schemas[] = new Schema(type: Schema\Type::BOOLEAN);
                    break;
                case Type::BUILTIN_TYPE_TRUE:
                    $schemas[] = new Schema(type: Schema\Type::BOOLEAN, enum: [true]);
                    break;
                case Type::BUILTIN_TYPE_FALSE:
                    $schemas[] = new Schema(type: Schema\Type::BOOLEAN, enum: [false]);
                    break;
                case Type::BUILTIN_TYPE_ARRAY:
                case Type::BUILTIN_TYPE_ITERABLE:
                    $subTypes = $type->getCollectionValueTypes();

                    $schemas[] = new Schema(
                        type: Schema\Type::ARRAY,
                        items: $this->getOpenApiSchemaFromTypes($subTypes, $reflectionProperty),
                    );
                    break;
                case Type::BUILTIN_TYPE_OBJECT:
                    if (null !== $type->getClassName()) {
                        /** @var class-string $className */
                        $className = $type->getClassName();

                        if (in_array($className, [\DateTime::class, \DateTimeImmutable::class, \DateTimeInterface::class], true)) {
                            $schemas[] = new Schema(type: Schema\Type::STRING, format: 'date-time');
                            break;
                        }

                        $schemas[] = new Schema(ref: $this->componentsRegistry->getSchemaReference($className));
                        break;
                    }

                    $schemas[] = new Schema(type: Schema\Type::OBJECT);
                    break;
                case Type::BUILTIN_TYPE_NULL:
                    $isNullable = true;
                    break;
                default:
                    throw new \RuntimeException(sprintf('Unsupported type "%s"', $type->getBuiltinType()));
            }
        }

        if ($isNullable) {
            $schemas[] = new Schema(type: Schema\Type::NULL);
        }

        match (count($schemas)) {
            0 => throw new \RuntimeException(
                sprintf('No type found for property "%s" in class "%s"', $reflectionProperty->getName(), $reflectionProperty->getDeclaringClass()->getName())
            ),
            1 => $schema = $schemas[0],
            default => $schema->anyOf = $schemas,
        };

        return $schema;
    }

    private function createPropertyInfoExtractor(): PropertyInfoExtractorInterface
    {
        $reflectionExtractor = new ReflectionExtractor();
        $phpDocExtractor = new PhpDocExtractor();
        $phpStanExtractor = new PhpStanExtractor();

        return new PropertyInfoExtractor(
            listExtractors: [$reflectionExtractor],
            typeExtractors: [$phpStanExtractor, $phpDocExtractor, $reflectionExtractor],
            descriptionExtractors: [$phpDocExtractor],
            accessExtractors: [$reflectionExtractor],
            initializableExtractors: [$reflectionExtractor],
        );
    }

    private function isPropertyAllowedOnlyNull(\ReflectionProperty $reflectionProperty): bool
    {
        $type = $reflectionProperty->getType();

        return $type instanceof \ReflectionNamedType && 'null' === $type->getName();
    }

    private function determineDefaultValue(\ReflectionProperty $reflectionProperty): mixed
    {
        if ($reflectionProperty->hasDefaultValue()) {
            return $reflectionProperty->getDefaultValue();
        }

        // Promoted properties do not inherit the default value from the constructor parameter, we need to fetch it from
        // the constructor parameter instead.
        if ($reflectionProperty->isPromoted()) {
            $propertyName = $reflectionProperty->getName();
            $constructorParameters = $reflectionProperty->getDeclaringClass()->getConstructor()?->getParameters();

            foreach ($constructorParameters ?? [] as $parameter) {
                if ($parameter->getName() === $propertyName) {
                    if ($parameter->isDefaultValueAvailable()) {
                        return $parameter->getDefaultValue();
                    }

                    return Undefined::VALUE;
                }
            }
        }

        return Undefined::VALUE;
    }

    /**
     * @param class-string<\UnitEnum> $className
     */
    private function getEnumSchema(string $className): Schema
    {
        $reflectionEnum = new \ReflectionEnum($className);

        return match ($reflectionEnum->getBackingType()?->getName()) {
            'string' => new Schema(
                type: Schema\Type::STRING,
                enum: array_map(
                    /** @phpstan-ignore-next-line */
                    static fn (\BackedEnum $enum): string => $enum->value,
                    $className::cases(),
                )
            ),
            'int' => new Schema(
                type: Schema\Type::INTEGER,
                enum: array_map(
                    /** @phpstan-ignore-next-line */
                    static fn (\BackedEnum $enum): int => $enum->value,
                    $className::cases(),
                )
            ),
            default => throw new \RuntimeException(sprintf('Unsupported enum "%s"', $className)),
        };
    }
}
