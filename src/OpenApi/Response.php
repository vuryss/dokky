<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class Response implements \JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * @param array<string, Header|Reference> $headers
     * @param array<string, MediaType>        $content
     * @param array<string, Link|Reference>   $links
     */
    public function __construct(
        public string $description,
        public Undefined|array $headers = Undefined::VALUE,
        public Undefined|array $content = Undefined::VALUE,
        public Undefined|array $links = Undefined::VALUE,
    ) {
    }
}
