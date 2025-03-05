<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class Encoding implements \JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * @param array<Header|Reference> $headers
     */
    public function __construct(
        public Undefined|string $contentType = Undefined::VALUE,
        public Undefined|array $headers = Undefined::VALUE,
        public Undefined|string $style = Undefined::VALUE,
        public Undefined|bool $explode = Undefined::VALUE,
        public Undefined|bool $allowReserved = Undefined::VALUE,
    ) {
    }
}
