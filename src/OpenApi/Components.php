<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class Components implements \JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * @param array<string, Schema>                                      $schemas
     * @param array<string, Response|Reference>                          $responses
     * @param array<string, Parameter|Reference>                         $parameters
     * @param array<string, Example|Reference>                           $examples
     * @param array<string, RequestBody|Reference>                       $requestBodies
     * @param array<string, Header|Reference>                            $headers
     * @param array<string, SecurityScheme|Reference>                    $securitySchemes
     * @param array<string, Link|Reference>                              $links
     * @param array<string, array<string, PathItem|Reference>|Reference> $callbacks
     * @param array<string, PathItem|Reference>                          $pathItems
     */
    public function __construct(
        public Undefined|array $schemas = Undefined::VALUE,
        public Undefined|array $responses = Undefined::VALUE,
        public Undefined|array $parameters = Undefined::VALUE,
        public Undefined|array $examples = Undefined::VALUE,
        public Undefined|array $requestBodies = Undefined::VALUE,
        public Undefined|array $headers = Undefined::VALUE,
        public Undefined|array $securitySchemes = Undefined::VALUE,
        public Undefined|array $links = Undefined::VALUE,
        public Undefined|array $callbacks = Undefined::VALUE,
        public Undefined|array $pathItems = Undefined::VALUE,
    ) {
    }
}
