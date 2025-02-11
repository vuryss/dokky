<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class RequestBody
{
    /**
     * @param array<string, MediaType> $content
     */
    public function __construct(
        public array $content,
        public ?string $description = null,
        public bool $required = false,
    ) {
    }
}
