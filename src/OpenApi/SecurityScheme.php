<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class SecurityScheme implements \JsonSerializable
{
    use JsonSerializableTrait;

    public function __construct(
        public SecurityScheme\Type $type,
        public Undefined|string $name = Undefined::VALUE,
        public Undefined|SecurityScheme\In $in = Undefined::VALUE,
        public Undefined|string $description = Undefined::VALUE,
        public Undefined|string $scheme = Undefined::VALUE,
        public Undefined|string $bearerFormat = Undefined::VALUE,
        public Undefined|string $flows = Undefined::VALUE,
        public Undefined|string $openIdConnectUrl = Undefined::VALUE,
    ) {
    }
}
