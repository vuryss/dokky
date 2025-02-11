<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class Example
{
    public function __construct(
        public ?string $summary = null,
        public ?string $description = null,
        public mixed $value = null,
        public ?string $externalValue = null,
    ) {
    }
}
