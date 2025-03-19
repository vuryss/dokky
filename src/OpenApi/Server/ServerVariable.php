<?php

declare(strict_types=1);

namespace Dokky\OpenApi\Server;

use Dokky\OpenApi\JsonSerializableTrait;
use Dokky\Undefined;

class ServerVariable implements \JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * @param array<string> $enum
     */
    public function __construct(
        public string $default,
        public Undefined|array $enum = Undefined::VALUE,
        public Undefined|string $description = Undefined::VALUE,
    ) {
    }
}
