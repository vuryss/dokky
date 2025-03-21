<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

use Dokky\Attribute\Property;
use Dokky\OpenApi\Schema;

class InvalidVariant5
{
    #[Property(schema: new Schema(enum: ['test1']))]
    #[Property(schema: new Schema(enum: ['test2']))]
    public string $property1;
}
