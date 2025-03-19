<?php

declare(strict_types=1);

namespace Dokky\Attribute;

use Dokky\DokkyException;

#[\Attribute(\Attribute::TARGET_CLASS)]
readonly class DiscriminatorMap
{
    /**
     * @param array<string, class-string> $mapping
     */
    public function __construct(
        public string $typeProperty,
        public array $mapping,
    ) {
        if ('' === $typeProperty) {
            throw new DokkyException('Type property cannot be an empty string.');
        }

        if ([] === $mapping) {
            throw new DokkyException('Mapping array cannot be empty.');
        }
    }
}
