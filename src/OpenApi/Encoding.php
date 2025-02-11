<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class Encoding
{
    /**
     * @param array<Header|Reference>|null $headers
     */
    public function __construct(
        public ?string $contentType = null,
        public ?array $headers = null,
        public ?string $style = null,
        public ?bool $explode = null,
        public bool $allowReserved = false,
    ) {
    }
}
