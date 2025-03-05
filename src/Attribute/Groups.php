<?php

declare(strict_types=1);

namespace Dokky\Attribute;

use Dokky\DokkyException;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
readonly class Groups
{
    /**
     * @param array<string> $groups Array of group names
     */
    public function __construct(
        public array $groups,
    ) {
        if (!$this->groups) {
            throw new DokkyException(sprintf('Parameter given to "%s" cannot be empty.', static::class));
        }

        foreach ($this->groups as $group) {
            /* @phpstan-ignore-next-line */
            if (!is_string($group) || '' === $group) {
                throw new DokkyException(sprintf(
                    'Parameter given to "%s" must be a string or an array of non-empty strings.',
                    static::class
                ));
            }
        }
    }
}
