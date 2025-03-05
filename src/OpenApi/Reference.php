<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class Reference implements \JsonSerializable
{
    use JsonSerializableTrait;

    public function __construct(
        public string $ref,
        public Undefined|string $summary = Undefined::VALUE,
        public Undefined|string $description = Undefined::VALUE,
    ) {
    }
}
