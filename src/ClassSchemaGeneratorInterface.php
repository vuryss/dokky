<?php

declare(strict_types=1);

namespace Dokky;

use Dokky\OpenApi\Schema;

interface ClassSchemaGeneratorInterface
{
    /**
     * @param class-string       $className
     * @param array<string>|null $groups
     */
    public function generate(string $className, ?array $groups = null): Schema;
}
