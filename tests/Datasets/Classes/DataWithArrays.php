<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

class DataWithArrays
{
    /**
     * @var array<string>
     */
    public array $arrayOfScalar;

    /**
     * @var array<MultiType|Basic>
     */
    public array $arrayOfObjects;

    /**
     * @var array<Basic|MultiType|int>
     */
    public array $arrayOfMultipleMixedTypes;
}
