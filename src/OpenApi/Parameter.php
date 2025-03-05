<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class Parameter implements \JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * @param array<string, Example|Reference> $examples
     * @param array<string, MediaType>         $content
     */
    public function __construct(
        public string $name,
        public In $in,
        public Undefined|string $description = Undefined::VALUE,
        public Undefined|bool $required = Undefined::VALUE,
        public Undefined|bool $deprecated = Undefined::VALUE,
        public Undefined|bool $allowEmptyValue = Undefined::VALUE,
        public Undefined|string $style = Undefined::VALUE,
        public Undefined|string $explode = Undefined::VALUE,
        public Undefined|bool $allowReserved = Undefined::VALUE,
        public Undefined|Schema $schema = Undefined::VALUE,
        public mixed $example = Undefined::VALUE,
        public Undefined|array $examples = Undefined::VALUE,
        public Undefined|array $content = Undefined::VALUE,
    ) {
        if (In::PATH === $in) {
            $this->required = true;
        }
    }
}
