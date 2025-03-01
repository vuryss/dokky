<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class Operation
{
    /**
     * @param array<string>                                                   $tags
     * @param array<Parameter|Reference>                                      $parameters
     * @param array<string, array<string, PathItem|Reference>|Reference>|null $callbacks
     * @param array<SecurityRequirement>                                      $security
     * @param array<Server>                                                   $servers
     */
    public function __construct(
        public array $tags = [],
        public ?string $summary = null,
        public ?string $description = null,
        public ?ExternalDocumentation $externalDocs = null,
        public ?string $operationId = null,
        public array $parameters = [],
        public RequestBody|Reference|null $requestBody = null,
        public ?Responses $responses = null,
        public ?array $callbacks = null,
        public bool $deprecated = false,
        public array $security = [],
        public array $servers = [],
    ) {
    }
}
