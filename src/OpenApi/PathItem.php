<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class PathItem implements \JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * @param array<Server>              $servers
     * @param array<Parameter|Reference> $parameters
     */
    public function __construct(
        public Undefined|string $ref = Undefined::VALUE,
        public Undefined|string $summary = Undefined::VALUE,
        public Undefined|string $description = Undefined::VALUE,
        public Undefined|Operation $get = Undefined::VALUE,
        public Undefined|Operation $put = Undefined::VALUE,
        public Undefined|Operation $post = Undefined::VALUE,
        public Undefined|Operation $delete = Undefined::VALUE,
        public Undefined|Operation $options = Undefined::VALUE,
        public Undefined|Operation $head = Undefined::VALUE,
        public Undefined|Operation $patch = Undefined::VALUE,
        public Undefined|Operation $trace = Undefined::VALUE,
        public Undefined|array $servers = Undefined::VALUE,
        public Undefined|array $parameters = Undefined::VALUE,
    ) {
    }
}
