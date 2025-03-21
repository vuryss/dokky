<?php

declare(strict_types=1);

namespace Dokky\Attribute;

use Dokky\OpenApi\Schema;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
readonly class Property
{
    public function __construct(
        public ?Schema $schema = null,
    ) {
    }
}
