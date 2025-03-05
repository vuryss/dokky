<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class OpenApi implements \JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * @param array<Server>                   $servers
     * @param array<string, PathItem>         $paths
     * @param array<string, PathItem>         $webhooks
     * @param Undefined|Tag[]                 $tags
     * @param Undefined|SecurityRequirement[] $security
     */
    public function __construct(
        public string $openapi,
        public Info $info,
        public Undefined|string $jsonSchemaDialect = Undefined::VALUE,
        public Undefined|array $servers = Undefined::VALUE,
        public Undefined|array $paths = Undefined::VALUE,
        public Undefined|array $webhooks = Undefined::VALUE,
        public Undefined|Components $components = Undefined::VALUE,
        public Undefined|array $security = Undefined::VALUE,
        public Undefined|array $tags = Undefined::VALUE,
        public Undefined|ExternalDocumentation $externalDocs = Undefined::VALUE,
    ) {
    }
}
