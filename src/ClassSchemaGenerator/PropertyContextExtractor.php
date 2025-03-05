<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator;

use Dokky\Attribute\Groups;
use Dokky\DokkyException;

readonly class PropertyContextExtractor
{
    public function extract(\ReflectionProperty $property): PropertyContext
    {
        return new PropertyContext(
            groups: $this->extractGroups($property),
            ignored: $this->extractIgnored($property),
            name: $this->extractName($property),
        );
    }

    /**
     * @return array<string>
     */
    private function extractGroups(\ReflectionProperty $property): array
    {
        // First priority - our attribute
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

        // Second priority - Symfony serializer attribute, if available
        if (class_exists(\Symfony\Component\Serializer\Attribute\Groups::class)) {
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
        }

        // Nothing found
        return [];
    }

    private function extractIgnored(\ReflectionProperty $property): bool
    {
        // First priority - our attribute
        $ignoreAttribute = $property->getAttributes(\Dokky\Attribute\Ignore::class);

        if (count($ignoreAttribute) > 0) {
            return true;
        }

        // Second priority - Symfony serializer attribute, if available
        if (class_exists(\Symfony\Component\Serializer\Attribute\Ignore::class)) {
            $symfonyIgnoreAttribute = $property->getAttributes(\Symfony\Component\Serializer\Attribute\Ignore::class);

            if (count($symfonyIgnoreAttribute) > 0) {
                return true;
            }
        }

        // Nothing found
        return false;
    }

    private function extractName(\ReflectionProperty $property): ?string
    {
        // First priority - our attribute
        $serializedNameAttribute = $property->getAttributes(\Dokky\Attribute\SerializedName::class);

        if (count($serializedNameAttribute) > 0) {
            return $serializedNameAttribute[0]->newInstance()->serializedName;
        }

        // Second priority - Symfony serializer attribute, if available
        if (class_exists(\Symfony\Component\Serializer\Attribute\SerializedName::class)) {
            $symfonySerializedNameAttribute = $property->getAttributes(
                \Symfony\Component\Serializer\Attribute\SerializedName::class
            );

            if (count($symfonySerializedNameAttribute) > 0) {
                return $symfonySerializedNameAttribute[0]->newInstance()->getSerializedName();
            }
        }

        // Nothing found
        return null;
    }
}
