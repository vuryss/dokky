<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator\PropertyContextReader;

use Dokky\Attribute\Constraints;
use Dokky\ClassSchemaGenerator\PropertyContext;
use Dokky\ClassSchemaGenerator\PropertyContextReaderInterface;
use Dokky\ClassSchemaGenerator\Util;
use Dokky\DokkyException;

readonly class SymfonyPropertyContextReader implements PropertyContextReaderInterface
{
    public function extract(\ReflectionProperty $property): PropertyContext
    {
        if (!class_exists(\Symfony\Component\Serializer\Attribute\SerializedName::class)) {
            return new PropertyContext(); // @codeCoverageIgnore
        }

        $constraints = $this->getConstraints($property);

        return new PropertyContext(
            groups: $this->extractGroups($property),
            ignored: $this->extractIgnored($property),
            name: $this->extractName($property),
            minLength: $constraints->minLength,
            maxLength: $constraints->maxLength,
            minItems: $constraints->minItems,
            maxItems: $constraints->maxItems,
            minimum: $constraints->minimum,
            maximum: $constraints->maximum,
            exclusiveMinimum: $constraints->exclusiveMinimum,
            exclusiveMaximum: $constraints->exclusiveMaximum,
        );
    }

    /**
     * @return array<string>
     */
    private function extractGroups(\ReflectionProperty $property): array
    {
        $symfonyGroupsAttribute = $property->getAttributes(\Symfony\Component\Serializer\Attribute\Groups::class);
        $attributeCount = count($symfonyGroupsAttribute);

        if ($attributeCount > 1) {
            throw new DokkyException(sprintf(
                'Property "%s" of class "%s" has multiple "%s" attributes, only one is allowed',
                $property->getName(),
                $property->getDeclaringClass()->getName(),
                \Symfony\Component\Serializer\Attribute\Groups::class,
            ));
        }

        if (1 === $attributeCount) {
            return Util::formatGroups($symfonyGroupsAttribute[0]->newInstance()->getGroups());
        }

        return [];
    }

    private function extractIgnored(\ReflectionProperty $property): bool
    {
        $symfonyIgnoreAttribute = $property->getAttributes(\Symfony\Component\Serializer\Attribute\Ignore::class);

        return count($symfonyIgnoreAttribute) > 0;
    }

    private function extractName(\ReflectionProperty $property): ?string
    {
        $symfonySerializedNameAttribute = $property->getAttributes(
            \Symfony\Component\Serializer\Attribute\SerializedName::class
        );

        if (count($symfonySerializedNameAttribute) > 0) {
            return $symfonySerializedNameAttribute[0]->newInstance()->getSerializedName();
        }

        return null;
    }

    private function getConstraints(\ReflectionProperty $property): Constraints
    {
        $constraintAttributes = $property->getAttributes(
            \Symfony\Component\Validator\Constraint::class,
            \ReflectionAttribute::IS_INSTANCEOF,
        );

        $minLength = null;
        $maxLength = null;
        $minItems = null;
        $maxItems = null;
        $minimum = null;
        $exclusiveMinimum = null;
        $maximum = null;
        $exclusiveMaximum = null;

        foreach ($constraintAttributes as $attribute) {
            $attribute = $attribute->newInstance();

            switch ($attribute::class) {
                case \Symfony\Component\Validator\Constraints\Length::class:
                    $minLength = $attribute->min;
                    $maxLength = $attribute->max;
                    break;

                case \Symfony\Component\Validator\Constraints\Count::class:
                    $minItems = $attribute->min;
                    $maxItems = $attribute->max;
                    break;

                case \Symfony\Component\Validator\Constraints\Range::class:
                    $minimum = $this->intOrFloat($attribute->min);
                    $maximum = $this->intOrFloat($attribute->max);
                    break;

                case \Symfony\Component\Validator\Constraints\LessThan::class:
                    $maximum = null;
                    $exclusiveMaximum = $this->intOrFloat($attribute->value);
                    break;

                case \Symfony\Component\Validator\Constraints\LessThanOrEqual::class:
                    $maximum = $this->intOrFloat($attribute->value);
                    $exclusiveMaximum = null;
                    break;

                case \Symfony\Component\Validator\Constraints\GreaterThan::class:
                    $minimum = null;
                    $exclusiveMinimum = $this->intOrFloat($attribute->value);
                    break;

                case \Symfony\Component\Validator\Constraints\GreaterThanOrEqual::class:
                    $minimum = $this->intOrFloat($attribute->value);
                    $exclusiveMinimum = null;
                    break;
            }
        }

        return new Constraints(
            minLength: $minLength,
            maxLength: $maxLength,
            minimum: $minimum,
            exclusiveMinimum: $exclusiveMinimum,
            maximum: $maximum,
            exclusiveMaximum: $exclusiveMaximum,
            minItems: $minItems,
            maxItems: $maxItems,
        );
    }

    private function intOrFloat(mixed $value): int|float
    {
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        if (!is_numeric($value)) {
            throw new DokkyException('Expected numeric value, got: '.get_debug_type($value));
        }

        if (str_contains($value, '.')) {
            return (float) $value;
        }

        return (int) $value;
    }
}
