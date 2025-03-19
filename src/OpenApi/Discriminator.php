<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class Discriminator implements \JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * @param array<string, string> $mapping
     */
    public function __construct(
        public string $propertyName,
        public Undefined|array $mapping = Undefined::VALUE,
    ) {
    }
}
