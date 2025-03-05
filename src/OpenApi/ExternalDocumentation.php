<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class ExternalDocumentation
{
    public function __construct(
        public string $url,
        public Undefined|string $description = Undefined::VALUE,
    ) {
    }
}
