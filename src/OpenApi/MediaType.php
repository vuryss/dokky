<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class MediaType
{
    /**
     * @param array<Example|Reference>|null $examples
     * @param array<Encoding>|null $encoding
     */
    public function __construct(
        public ?Schema $schema = null,
        public mixed $example = null,
        public ?array $examples = null,
        public ?array $encoding = null,
    ) {
    }
}
