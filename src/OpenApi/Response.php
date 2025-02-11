<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class Response
{
    /**
     * @param array<string, Header|Reference> $headers
     * @param array<string, MediaType> $content
     * @param array<string, Link|Reference> $links
     */
    public function __construct(
        public string $description,
        public array $headers = [],
        public array $content = [],
        public array $links = [],
    ) {
    }
}
