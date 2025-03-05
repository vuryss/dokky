<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

use Dokky\Attribute\Ignore as DokkyIgnore;
use Symfony\Component\Serializer\Attribute\Ignore as SymfonyIgnore;

class DataWithIgnore
{
    #[SymfonyIgnore]
    public string $property1;

    #[DokkyIgnore]
    public string $property2;

    public string $property3;

    public function __construct(
        #[SymfonyIgnore]
        public string $property4,

        #[DokkyIgnore]
        public string $property5,

        public string $property6,
    ) {
    }
}
