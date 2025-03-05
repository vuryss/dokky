<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator;

use Dokky\Undefined;

readonly class PropertyContext
{
    /**
     * @param Undefined|array<string> $groups
     */
    public function __construct(
        public Undefined|array $groups = Undefined::VALUE,
    ) {
    }
}
