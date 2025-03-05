<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class Server implements \JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * @param array<Server\ServerVariable> $variables
     */
    public function __construct(
        public string $url,
        public Undefined|string $description = Undefined::VALUE,
        public Undefined|array $variables = Undefined::VALUE,
    ) {
    }
}
