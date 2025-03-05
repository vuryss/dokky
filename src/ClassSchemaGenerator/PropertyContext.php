<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator;

readonly class PropertyContext
{
    /**
     * @param array<string> $groups
     */
    public function __construct(
        public array $groups = [],
        public bool $ignored = false,
        public ?string $name = null,
    ) {
    }
}
