<?php

declare(strict_types=1);

namespace Dokky;

class SchemaRegistry
{
    /**
     * @psalm-type GroupName = string
     *
     * @var array<class-string, array<GroupName, string>>
     */
    private array $classNameGroupReference = [];

    /**
     * @var array<string, bool>
     */
    private array $existingSchemas = [];

    /**
     * @param class-string $className
     */
    public function getReference(string $className): string
    {
        if (!isset($this->classNameGroupReference[$className][Undefined::VALUE->value])) {
            try {
                $shortClassName = new \ReflectionClass($className)->getShortName();
            } catch (\ReflectionException $e) {
                throw new \RuntimeException('Class '.$className.' does not exist', previous: $e);
            }

            $ref = '#/components/schemas/'.$shortClassName;

            if (isset($this->existingSchemas[$ref])) {
                $suffix = 2;

                while (isset($this->existingSchemas[$ref.$suffix])) {
                    ++$suffix;
                }

                $ref .= $suffix;
            }

            $this->classNameGroupReference[$className][Undefined::VALUE->value] = $ref;
            $this->existingSchemas[$ref] = true;
        }

        return $this->classNameGroupReference[$className][Undefined::VALUE->value];
    }
}
