<?php

declare(strict_types=1);

namespace Dokky\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
readonly class Groups
{
    /**
     * @param array<string> $names Array of group names
     */
    public function __construct(
        public array $names,
    ) {
    }
}
