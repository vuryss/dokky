<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class Reference
{
    public function __construct(
        public string $ref,
        public ?string $summary = null,
        public ?string $description = null,
    ) {
    }
}
