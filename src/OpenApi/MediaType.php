<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class MediaType implements \JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * @param array<Example|Reference> $examples
     * @param array<string, Encoding>  $encoding
     */
    public function __construct(
        public Undefined|Schema $schema = Undefined::VALUE,
        public mixed $example = Undefined::VALUE,
        public Undefined|array $examples = Undefined::VALUE,
        public Undefined|array $encoding = Undefined::VALUE,
    ) {
    }
}
