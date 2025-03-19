<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

use Symfony\Component\Serializer\Attribute\DiscriminatorMap;

#[DiscriminatorMap(
    typeProperty: 'type',
    mapping: [
        'type1' => DataWithEnums::class,
        'type2' => Basic::class,
    ]
)]
interface DataWithDiscriminatorMap3
{
}
