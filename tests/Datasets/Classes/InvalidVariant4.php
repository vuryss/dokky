<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

use Symfony\Component\Validator\Constraints\GreaterThan;

class InvalidVariant4
{
    #[GreaterThan('test1')]
    public string $property1;
}
