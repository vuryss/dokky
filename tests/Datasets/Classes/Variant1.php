<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

class Variant1
{
    public Basic|MultiType|int|null $property = 5;
}
