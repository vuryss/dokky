<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class Tag
{
    public function __construct(
        public string $name,
        public ?string $description = null,
        public ?ExternalDocumentation $externalDocs = null,
    ) {
    }
}
