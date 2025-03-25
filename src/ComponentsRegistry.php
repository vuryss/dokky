<?php

declare(strict_types=1);

namespace Dokky;

class ComponentsRegistry
{
    private const string NO_GROUP_DEFINED = '67dd70e96b6db';

    /**
     * @var array<class-string, array<string, string>>
     */
    private array $schemaNamesByClassNameAndGroup = [];

    /**
     * @var array<string, string>
     */
    private array $existingSchemas = [];

    /**
     * Groups hash to array of group names.
     *
     * @var array<string, string[]|null>
     */
    private array $groupsMap = [
        self::NO_GROUP_DEFINED => null,
    ];

    /**
     * @var array<class-string, true>
     */
    private array $uniqueClassNames = [];

    /**
     * @param class-string  $className
     * @param array<string> $groups
     */
    public function getSchemaReference(string $className, ?array $groups = null): string
    {
        $groupHash = self::NO_GROUP_DEFINED;

        if (null !== $groups) {
            sort($groups);
            $groupHash = 'hash-'.crc32(implode(',', $groups));
            $this->groupsMap[$groupHash] = $groups;
        }

        if (!isset($this->schemaNamesByClassNameAndGroup[$className][$groupHash])) {
            try {
                $shortClassName = new \ReflectionClass($className)->getShortName();
            } catch (\ReflectionException $e) {
                throw new DokkyException(
                    sprintf('Cannot get reference for class "%s" - class does not exist', $className),
                    previous: $e
                );
            }

            $schemaName = $shortClassName;
            $refPrefix = '#/components/schemas/';

            if (isset($this->existingSchemas[$schemaName])) {
                $suffix = 2;

                while (isset($this->existingSchemas[$schemaName.$suffix])) {
                    ++$suffix;
                }

                $schemaName .= $suffix;
            }

            $this->schemaNamesByClassNameAndGroup[$className][$groupHash] = $schemaName;
            $this->existingSchemas[$schemaName] = $refPrefix.$schemaName;
            $this->uniqueClassNames[$className] = true;
        }

        $schemaName = $this->schemaNamesByClassNameAndGroup[$className][$groupHash];

        return '#/components/schemas/'.$schemaName;
    }

    /**
     * @return list<array{className: class-string, groups: string[]|null, schemaName: string}>
     */
    public function getSchemaComponents(): array
    {
        $schemaComponents = [];

        foreach ($this->schemaNamesByClassNameAndGroup as $className => $groupReference) {
            foreach ($groupReference as $groupHash => $schemaName) {
                $schemaComponents[] = [
                    'className' => $className,
                    'groups' => $this->groupsMap[$groupHash],
                    'schemaName' => $schemaName,
                ];
            }
        }

        return $schemaComponents;
    }

    /**
     * Useful for debugging and also determine the classes to watch for changes when caching.
     *
     * @return array<class-string>
     */
    public function getUniqueClassNames(): array
    {
        return array_keys($this->uniqueClassNames);
    }
}
