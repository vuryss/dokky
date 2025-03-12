<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

use Dokky\Attribute\Groups;

class InvalidVariant1
{
    #[Groups(['test1'])]
    #[Groups(['test2'])]
    public string $property1;
}
