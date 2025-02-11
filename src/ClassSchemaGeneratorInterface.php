<?php

declare(strict_types=1);

namespace Dokky;

use Dokky\OpenApi\Schema;

interface ClassSchemaGeneratorInterface
{
    public function generate(string $className): Schema;
}
