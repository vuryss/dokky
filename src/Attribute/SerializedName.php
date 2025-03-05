<?php

declare(strict_types=1);

namespace Dokky\Attribute;

use Dokky\DokkyException;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
readonly class SerializedName
{
    public function __construct(
        public string $serializedName,
    ) {
        if ('' === $serializedName) {
            throw new DokkyException(\sprintf(
                'Parameter given to "%s" must be a non-empty string.',
                self::class
            ));
        }
    }
}
