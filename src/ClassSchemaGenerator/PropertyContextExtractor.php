<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator;

use Dokky\Attribute\Groups;
use Dokky\DokkyException;
use Dokky\Undefined;

readonly class PropertyContextExtractor
{
    public function extract(\ReflectionProperty $property): PropertyContext
    {
        return new PropertyContext(
            groups: $this->extractGroups($property),
        );
    }

    /**
     * @return array<string>|Undefined
     */
    private function extractGroups(\ReflectionProperty $property): array|Undefined
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
            return Util::formatGroups($groupsAttribute[0]->newInstance()->names);
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
        return Undefined::VALUE;
    }
}
