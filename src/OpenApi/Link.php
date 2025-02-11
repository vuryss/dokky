<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class Link
{
    /**
     * @param array<string, mixed>|null $parameters
     */
    public function __construct(
        public ?string $operationRef = null,
        public ?string $operationId = null,
        public ?array $parameters = null,
        public mixed $requestBody = null,
        public ?string $description = null,
        public ?Server $server = null,
    ) {
    }
}
