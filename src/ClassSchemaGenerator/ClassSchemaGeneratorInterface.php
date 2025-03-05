<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator;

use Dokky\OpenApi\Schema;

interface ClassSchemaGeneratorInterface
{
    /**
     * @param array<string>|null $groups
     */
    public function generate(string $className, ?array $groups = null): Schema;
}
