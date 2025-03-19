<?php

declare(strict_types=1);

namespace Dokky\OpenApi\Info;

use Dokky\OpenApi\JsonSerializableTrait;
use Dokky\Undefined;

class License implements \JsonSerializable
{
    use JsonSerializableTrait;

    public function __construct(
        public Undefined|string $name = Undefined::VALUE,
        public Undefined|string $identifier = Undefined::VALUE,
        public Undefined|string $url = Undefined::VALUE,
    ) {
    }
}
