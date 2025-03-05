<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class Example implements \JsonSerializable
{
    use JsonSerializableTrait;

    public function __construct(
        public Undefined|string $summary = Undefined::VALUE,
        public Undefined|string $description = Undefined::VALUE,
        public mixed $value = Undefined::VALUE,
        public Undefined|string $externalValue = Undefined::VALUE,
    ) {
    }
}
