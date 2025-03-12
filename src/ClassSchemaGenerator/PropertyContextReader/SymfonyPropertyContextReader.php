<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator\PropertyContextReader;

use Dokky\ClassSchemaGenerator\PropertyContext;
use Dokky\ClassSchemaGenerator\PropertyContextReaderInterface;
use Dokky\ClassSchemaGenerator\Util;
use Dokky\DokkyException;

readonly class SymfonyPropertyContextReader implements PropertyContextReaderInterface
{
    public function extract(\ReflectionProperty $property): PropertyContext
    {
        if (!class_exists(\Symfony\Component\Serializer\Attribute\SerializedName::class)) {
            return new PropertyContext();
        }

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
}
