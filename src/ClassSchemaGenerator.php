<?php

declare(strict_types=1);

namespace Dokky;

use Dokky\OpenApi\Schema;
use Dokky\OpenApi\Undefined;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpStanExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;

readonly class ClassSchemaGenerator implements ClassSchemaGeneratorInterface
{
    private PropertyInfoExtractorInterface $propertyInfoExtractor;

    public function __construct(
        ?PropertyInfoExtractorInterface $propertyInfoExtractor = null,
    ) {
        // TODO: Use cached extractor
        $this->propertyInfoExtractor = $propertyInfoExtractor ?? $this->createPropertyInfoExtractor();
    }

    public function generate(string $className): Schema
    {
        $schema = new Schema(
            type: Schema\Type::OBJECT,
            properties: [],
        );
        $required = [];
        $propertyNames = $this->propertyInfoExtractor->getProperties($className);

        foreach ($propertyNames as $propertyName) {
            $schema->properties[$propertyName] = $this->generatePropertySchema($className, $propertyName);

            if (Undefined::VALUE === $schema->properties[$propertyName]->default) {
                $required[] = $propertyName;
            }
        }

        $schema->required = $required;

        return $schema;
    }

    private function generatePropertySchema(string $className, mixed $propertyName): Schema
    {
        $foundTypes = $this->propertyInfoExtractor->getTypes($className, $propertyName);
        $reflectionProperty = new \ReflectionProperty($className, $propertyName);
        $isNullable = false;
        $property = new Schema(
            type: [],
        );

        if ($reflectionProperty->hasDefaultValue()) {
            $property->default = $reflectionProperty->getDefaultValue();
        }

        if (count($foundTypes) > 1) {
            throw new \RuntimeException(sprintf('Multiple types found for property "%s" of class "%s"', $propertyName, $className));
        }

        foreach ($foundTypes as $foundType) {
            if ($foundType->isNullable() && !in_array('null', $property->type, true)) {
                $isNullable = true;
            }

            switch ($foundType->getBuiltinType()) {
                case 'int':
                    $property->type[] = Schema\Type::INTEGER;
                    break;
                case 'float':
                    $property->type[] = Schema\Type::NUMBER;
                    $property->format = 'float';
                    break;
                case 'string':
                    $property->type[] = Schema\Type::STRING;
                    break;
                case 'bool':
                    $property->type[] = Schema\Type::BOOLEAN;
                    break;
                case 'array':
                    $property->type[] = Schema\Type::ARRAY;
                    break;
                case 'object':
                    $property->type[] = Schema\Type::OBJECT;
                    break;
                default:
                    throw new \RuntimeException(sprintf('Unsupported type "%s"', $foundType->getBuiltinType()));
            }
        }

        if ($isNullable) {
            $property->type[] = Schema\Type::NULL;
        }

        if (1 === count($property->type)) {
            $property->type = $property->type[0];
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
}
