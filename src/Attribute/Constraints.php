<?php

declare(strict_types=1);

namespace Dokky\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
readonly class Constraints
{
    public function __construct(
        public ?int $minLength = null,
        public ?int $maxLength = null,
        public int|float|null $minimum = null,
        public int|float|null $exclusiveMinimum = null,
        public int|float|null $maximum = null,
        public int|float|null $exclusiveMaximum = null,
        public ?int $minItems = null,
        public ?int $maxItems = null,
    ) {
    }
}
