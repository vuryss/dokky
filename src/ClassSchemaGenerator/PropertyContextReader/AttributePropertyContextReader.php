<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator\PropertyContextReader;

use Dokky\Attribute\Constraints;
use Dokky\Attribute\Groups;
use Dokky\ClassSchemaGenerator\PropertyContext;
use Dokky\ClassSchemaGenerator\PropertyContextReaderInterface;
use Dokky\ClassSchemaGenerator\Util;
use Dokky\DokkyException;

readonly class AttributePropertyContextReader implements PropertyContextReaderInterface
{
    public function extract(\ReflectionProperty $property): PropertyContext
    {
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
        $groupsAttribute = $property->getAttributes(Groups::class);
        $attributeCount = count($groupsAttribute);

        if ($attributeCount > 1) {
            throw new DokkyException(sprintf(
                'Property "%s" of class "%s" has multiple "%s" attributes, only one is allowed',
                $property->getName(),
                $property->getDeclaringClass()->getName(),
                Groups::class,
            ));
        }

        if (1 === $attributeCount) {
            return Util::formatGroups($groupsAttribute[0]->newInstance()->groups);
        }

        return [];
    }

    private function extractIgnored(\ReflectionProperty $property): bool
    {
        return count($property->getAttributes(\Dokky\Attribute\Ignore::class)) > 0;
    }

    private function extractName(\ReflectionProperty $property): ?string
    {
        $serializedNameAttribute = $property->getAttributes(\Dokky\Attribute\SerializedName::class);

        if (count($serializedNameAttribute) > 0) {
            return $serializedNameAttribute[0]->newInstance()->serializedName;
        }

        return null;
    }

    private function getConstraints(\ReflectionProperty $property): Constraints
    {
        $attributes = $property->getAttributes(Constraints::class);

        if (count($attributes) > 1) {
            throw new DokkyException(sprintf(
                'Property "%s" of class "%s" has multiple "%s" attributes, only one is allowed',
                $property->getName(),
                $property->getDeclaringClass()->getName(),
                Constraints::class,
            ));
        }

        if (1 === count($attributes)) {
            return $attributes[0]->newInstance();
        }

        return new Constraints();
    }
}
