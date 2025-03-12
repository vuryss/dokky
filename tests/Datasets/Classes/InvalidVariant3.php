<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

use Symfony\Component\Serializer\Attribute\Groups;

class InvalidVariant3
{
    #[Groups(['test1'])]
    #[Groups(['test2'])]
    public string $property1;
}
