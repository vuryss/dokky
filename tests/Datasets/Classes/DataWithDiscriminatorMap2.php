<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

use Dokky\Attribute\DiscriminatorMap;

#[DiscriminatorMap(
    typeProperty: 'type',
    mapping: [
        'type1' => DataWithEnums::class,
        'type2' => Basic::class,
    ]
)]
abstract class DataWithDiscriminatorMap2
{
}
