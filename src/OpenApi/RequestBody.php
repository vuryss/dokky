<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class RequestBody implements \JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * @param array<string, MediaType> $content
     */
    public function __construct(
        public array $content,
        public Undefined|string $description = Undefined::VALUE,
        public Undefined|bool $required = Undefined::VALUE,
    ) {
    }
}
