<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator;

use Dokky\Attribute\DiscriminatorMap;
use Dokky\ClassSchemaGenerator\PropertyContextReader\AttributePropertyContextReader;
use Dokky\ClassSchemaGenerator\PropertyContextReader\ChainPropertyContextReader;
use Dokky\ClassSchemaGenerator\PropertyContextReader\SymfonyPropertyContextReader;
use Dokky\ClassSchemaGeneratorInterface;
use Dokky\ComponentsRegistry;
use Dokky\Configuration;
use Dokky\DokkyException;
use Dokky\OpenApi\Discriminator;
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
    private Configuration $configuration;

    public function __construct(
        ?PropertyInfoExtractorInterface $propertyInfoExtractor = null,
        ?ComponentsRegistry $componentsRegistry = null,
        ?PropertyContextReaderInterface $propertyContextReader = null,
        ?Configuration $configuration = null,
    ) {
        $this->propertyInfoExtractor = $propertyInfoExtractor ?? $this->createPropertyInfoExtractor();
        $this->componentsRegistry = $componentsRegistry ?? new ComponentsRegistry();
        $this->propertyContextReader = $propertyContextReader ?? new ChainPropertyContextReader([
            new AttributePropertyContextReader(),
            new SymfonyPropertyContextReader(),
        ]);
        $this->configuration = $configuration ?? new Configuration();
    }

    public function generate(string $className, ?array $groups = null): Schema
    {
        if (enum_exists($className)) {
            return $this->getEnumSchema($className);
        }

        if (null !== $groups) {
            $groups = array_unique([...Util::formatGroups($groups), 'default']);
        }

        $reflectionClass = new \ReflectionClass($className);

        if ($reflectionClass->isAbstract() || $reflectionClass->isInterface()) {
            return $this->getInterfaceSchema($groups, $reflectionClass);
        }

        $schema = new Schema(
            type: Schema\Type::OBJECT,
            properties: [],
        );
        $required = [];
        $propertyNames = $this->propertyInfoExtractor->getProperties($className);

        if (null === $propertyNames) {
            throw new DokkyException(sprintf('No properties found for class "%s"', $className));
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

            if ($this->isPropertyRequired($propertySchema)) {
                $required[] = $finalName;
            }

            if (null !== $propertyContext->schema) {
                $propertySchema->manualOverwrite($propertyContext->schema);
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
        $shortDescription = $this->propertyInfoExtractor->getShortDescription($className, $propertyName);
        $longDescription = $this->propertyInfoExtractor->getLongDescription($className, $propertyName);
        $description = trim(($shortDescription ?? '').($longDescription ? "\n".$longDescription : ''));

        if (null === $foundTypes) {
            if ($this->isPropertyAllowedOnlyNull($reflectionProperty)) {
                $schema = new Schema(type: Schema\Type::NULL);
            } else {
                throw new DokkyException(
                    sprintf('No type found for property "%s" in class "%s"', $propertyName, $className)
                );
            }
        } else {
            $schema = $this->getOpenApiSchemaFromTypes($foundTypes, $reflectionProperty);
        }

        $schema->default = $this->determineDefaultValue($reflectionProperty);

        if ('' !== $description) {
            $schema->description = $description;
        }

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
                    $keyTypes = $type->getCollectionKeyTypes();

                    if ([] === $keyTypes) {
                        $schemas[] = new Schema(
                            type: Schema\Type::ARRAY,
                            items: $this->getOpenApiSchemaFromTypes($subTypes, $reflectionProperty),
                        );

                        break;
                    }

                    if (count($keyTypes) > 1) {
                        throw new DokkyException(
                            sprintf('Cannot handle multiple key types for property "%s"', $reflectionProperty->getName())
                        );
                    }

                    switch ($keyTypes[0]->getBuiltinType()) {
                        case 'string':
                            $schemas[] = new Schema(
                                type: Schema\Type::OBJECT,
                                additionalProperties: $this->getOpenApiSchemaFromTypes($subTypes, $reflectionProperty),
                            );
                            break 2;

                        case 'int':
                            $schemas[] = new Schema(
                                type: Schema\Type::ARRAY,
                                items: $this->getOpenApiSchemaFromTypes($subTypes, $reflectionProperty),
                            );
                            break 2;

                        default:
                            throw new DokkyException(
                                sprintf(
                                    'Cannot handle key type "%s" for property "%s"',
                                    $keyTypes[0]->getBuiltinType(),
                                    $reflectionProperty->getName()
                                )
                            );
                    }
                    // no break
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

    /**
     * @param array<string>|null       $groups
     * @param \ReflectionClass<object> $reflectionClass
     */
    private function getInterfaceSchema(
        ?array $groups,
        \ReflectionClass $reflectionClass,
    ): Schema {
        $discriminatorMap = $reflectionClass->getAttributes(DiscriminatorMap::class);

        if (1 === count($discriminatorMap)) {
            $discriminatorMap = $discriminatorMap[0]->newInstance();

            return $this->getDiscriminatorMapSchema(
                propertyName: $discriminatorMap->typeProperty,
                mapping: $discriminatorMap->mapping,
                groups: $groups,
            );
        }

        $discriminatorMap = $reflectionClass->getAttributes(\Symfony\Component\Serializer\Attribute\DiscriminatorMap::class);

        if (1 === count($discriminatorMap)) {
            $discriminatorMap = $discriminatorMap[0]->newInstance();

            return $this->getDiscriminatorMapSchema(
                propertyName: $discriminatorMap->getTypeProperty(),
                /* @phpstan-ignore-next-line */
                mapping: $discriminatorMap->getMapping(),
                groups: $groups,
            );
        }

        throw new DokkyException('Could not resolve DiscriminatorMap attribute for class: '.$reflectionClass->getName());
    }

    /**
     * @param array<string, class-string> $mapping
     * @param array<string>|null          $groups
     */
    private function getDiscriminatorMapSchema(string $propertyName, array $mapping, ?array $groups): Schema
    {
        return new Schema(
            type: Schema\Type::OBJECT,
            anyOf: array_map(
                fn ($className) => new Schema(
                    ref: $this->componentsRegistry->getSchemaReference($className, $groups),
                ),
                array_values($mapping),
            ),
            discriminator: new Discriminator(
                propertyName: $propertyName,
                mapping: array_map(
                    fn ($className) => $this->componentsRegistry->getSchemaReference($className, $groups),
                    $mapping
                ),
            ),
        );
    }

    private function isPropertyRequired(Schema $propertySchema): bool
    {
        // If property has default value it is not required
        if (Undefined::VALUE !== $propertySchema->default) {
            return false;
        }

        // Property does not have a default value - check if we have the flag for making nullable properties not
        // required and if we have a null as a possible type
        if (
            $this->configuration->considerNullablePropertiesAsNotRequired
            && (
                Schema\Type::NULL === $propertySchema->type
                || (
                    is_array($propertySchema->type)
                    && array_any($propertySchema->type, static fn ($type) => Schema\Type::NULL === $type)
                )
                || (
                    is_array($propertySchema->anyOf)
                    && array_any($propertySchema->anyOf, static fn ($schema) => Schema\Type::NULL === $schema->type)
                )
            )
        ) {
            return false;
        }

        return true;
    }
}
