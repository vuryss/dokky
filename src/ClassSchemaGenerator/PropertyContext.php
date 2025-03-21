<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator;

use Dokky\OpenApi\Schema;

readonly class PropertyContext
{
    /**
     * @param array<string> $groups
     */
    public function __construct(
        public array $groups = [],
        public bool $ignored = false,
        public ?string $name = null,
        public ?int $minLength = null,
        public ?int $maxLength = null,
        public ?int $minItems = null,
        public ?int $maxItems = null,
        public int|float|null $minimum = null,
        public int|float|null $maximum = null,
        public int|float|null $exclusiveMinimum = null,
        public int|float|null $exclusiveMaximum = null,
        public ?Schema $schema = null,
    ) {
    }
}
