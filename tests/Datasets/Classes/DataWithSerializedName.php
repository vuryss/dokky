<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

use Dokky\Attribute\SerializedName as DokkySerializedName;
use Symfony\Component\Serializer\Attribute\SerializedName as SymfonySerializedName;

class DataWithSerializedName
{
    #[SymfonySerializedName('property_1')]
    public string $property1;

    #[DokkySerializedName('property_2')]
    public string $property2;

    public string $property3;

    public function __construct(
        #[SymfonySerializedName('property_4')]
        public string $property4,

        #[DokkySerializedName('property_5')]
        public string $property5,

        public string $property6,
    ) {
    }
}
