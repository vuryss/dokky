<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class Tag implements \JsonSerializable
{
    use JsonSerializableTrait;

    public function __construct(
        public string $name,
        public Undefined|string $description = Undefined::VALUE,
        public Undefined|ExternalDocumentation $externalDocs = Undefined::VALUE,
    ) {
    }
}
