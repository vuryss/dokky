<?php

declare(strict_types=1);

namespace Dokky;

use Dokky\OpenApi\Schema;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpStanExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

readonly class ClassSchemaGenerator implements ClassSchemaGeneratorInterface
{
    private PropertyInfoExtractorInterface $propertyInfoExtractor;
    private SchemaRegistry $schemaRegistry;

    public function __construct(
        ?PropertyInfoExtractorInterface $propertyInfoExtractor = null,
        ?SchemaRegistry $schemaRegistry = null,
    ) {
        // TODO: Use cached extractor
        $this->propertyInfoExtractor = $propertyInfoExtractor ?? $this->createPropertyInfoExtractor();
        $this->schemaRegistry = $schemaRegistry ?? new SchemaRegistry();
    }

    public function generate(string $className): Schema
    {
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
            $propertySchema = $this->generatePropertySchema($className, $propertyName);
            $properties[$propertyName] = $propertySchema;

            if (Undefined::VALUE === $propertySchema->default) {
                $required[] = $propertyName;
            }
        }

        $schema->properties = $properties;

        if (count($required) > 0) {
            $schema->required = $required;
        }

        return $schema;
    }

    private function generatePropertySchema(string $className, string $propertyName): Schema
    {
        $foundTypes = $this->propertyInfoExtractor->getTypes($className, $propertyName);
        $reflectionProperty = new \ReflectionProperty($className, $propertyName);
        $isNullable = false;
        $property = new Schema();
        $types = [];

        if (null === $foundTypes) {
            $isNullable = $this->isPropertyAllowedOnlyNull($reflectionProperty);
            $foundTypes = [];
        }

        foreach ($foundTypes as $foundType) {
            $isNullable = $isNullable || $foundType->isNullable();

            switch ($foundType->getBuiltinType()) {
                case Type::BUILTIN_TYPE_INT:
                    $types[] = new Schema(type: Schema\Type::INTEGER);
                    break;
                case Type::BUILTIN_TYPE_FLOAT:
                    $types[] = new Schema(type: Schema\Type::NUMBER, format: 'float');
                    break;
                case Type::BUILTIN_TYPE_STRING:
                    $types[] = new Schema(type: Schema\Type::STRING);
                    break;
                case Type::BUILTIN_TYPE_BOOL:
                    $types[] = new Schema(type: Schema\Type::BOOLEAN);
                    break;
                case Type::BUILTIN_TYPE_TRUE:
                    $types[] = new Schema(type: Schema\Type::BOOLEAN, enum: [true]);
                    break;
                case Type::BUILTIN_TYPE_FALSE:
                    $types[] = new Schema(type: Schema\Type::BOOLEAN, enum: [false]);
                    break;
                case Type::BUILTIN_TYPE_ARRAY:
                case Type::BUILTIN_TYPE_ITERABLE:
                    $types[] = new Schema(type: Schema\Type::ARRAY);
                    break;
                case Type::BUILTIN_TYPE_OBJECT:
                    if (null !== $foundType->getClassName()) {
                        /** @var class-string $className */
                        $className = $foundType->getClassName();
                        $types[] = new Schema(ref: $this->schemaRegistry->getReference($className));
                        break;
                    }

                    $types[] = new Schema(type: Schema\Type::OBJECT);
                    break;
                case Type::BUILTIN_TYPE_NULL:
                    $isNullable = true;
                    break;
                default:
                    throw new \RuntimeException(sprintf('Unsupported type "%s"', $foundType->getBuiltinType()));
            }
        }

        if ($isNullable) {
            $types[] = new Schema(type: Schema\Type::NULL);
        }

        match (count($types)) {
            0 => throw new \RuntimeException(
                sprintf('No type found for property "%s" in class "%s"', $propertyName, $className)
            ),
            1 => $property = $types[0],
            default => $property->anyOf = $types,
        };

        if ($reflectionProperty->hasDefaultValue()) {
            $property->default = $reflectionProperty->getDefaultValue();
        }

        return $property;
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
}
