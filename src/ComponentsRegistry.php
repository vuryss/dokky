<?php

declare(strict_types=1);

namespace Dokky;

class ComponentsRegistry
{
    /**
     * @var array<class-string, array<string, string>>
     */
    private array $schemaNamesByClassNameAndGroup = [];

    /**
     * @var array<string, string>
     */
    private array $existingSchemas = [];

    /**
     * @param class-string  $className
     * @param array<string> $groups
     */
    public function getSchemaReference(string $className, ?array $groups = null): string
    {
        $group = Undefined::VALUE->value;

        if (null !== $groups) {
            sort($groups);
            $group = implode(',', $groups);
        }

        if (!isset($this->schemaNamesByClassNameAndGroup[$className][$group])) {
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

            $this->schemaNamesByClassNameAndGroup[$className][$group] = $schemaName;
            $this->existingSchemas[$schemaName] = $refPrefix.$schemaName;
        }

        $schemaName = $this->schemaNamesByClassNameAndGroup[$className][$group];

        return '#/components/schemas/'.$schemaName;
    }

    /**
     * @return list<array{className: class-string, groupName: string, schemaName: string}>
     */
    public function getSchemaComponents(): array
    {
        $schemaComponents = [];

        foreach ($this->schemaNamesByClassNameAndGroup as $className => $groupReference) {
            foreach ($groupReference as $groupName => $schemaName) {
                $schemaComponents[] = [
                    'className' => $className,
                    'groupName' => $groupName,
                    'schemaName' => $schemaName,
                ];
            }
        }

        return $schemaComponents;
    }
}
