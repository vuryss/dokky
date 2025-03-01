<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

readonly class Responses
{
    public function __construct(
        public Undefined|Response|Reference $default = Undefined::VALUE,
    ) {
    }
}
