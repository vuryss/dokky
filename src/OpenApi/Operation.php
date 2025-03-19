<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class Operation implements \JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * @param array<string>                                                        $tags
     * @param array<Parameter|Reference>                                           $parameters
     * @param array<string, array<string, PathItem|Reference>|Reference>|Undefined $callbacks
     * @param array<string, string[]>                                              $security
     * @param array<Server>                                                        $servers
     * @param Undefined|array<string, Response|Reference>                          $responses
     */
    public function __construct(
        public Undefined|array $tags = Undefined::VALUE,
        public Undefined|string $summary = Undefined::VALUE,
        public Undefined|string $description = Undefined::VALUE,
        public Undefined|ExternalDocumentation $externalDocs = Undefined::VALUE,
        public Undefined|string $operationId = Undefined::VALUE,
        public Undefined|array $parameters = Undefined::VALUE,
        public Undefined|RequestBody|Reference|null $requestBody = Undefined::VALUE,
        public Undefined|array $responses = Undefined::VALUE,
        public Undefined|array $callbacks = Undefined::VALUE,
        public Undefined|bool $deprecated = Undefined::VALUE,
        public Undefined|array $security = Undefined::VALUE,
        public Undefined|array $servers = Undefined::VALUE,
    ) {
    }
}
