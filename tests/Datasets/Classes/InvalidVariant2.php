<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

use Dokky\Attribute\Constraints;

class InvalidVariant2
{
    #[Constraints]
    #[Constraints]
    public string $property1;
}
