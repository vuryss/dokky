<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

use Dokky\Attribute\Property;
use Dokky\OpenApi\Schema;

class DataWithSchemaOverwrite
{
    #[Property(
        schema: new Schema(
            description: 'Some description',
            examples: ['test1', 'test2'],
            enum: ['test1', 'test2', 'test3']
        )
    )]
    public string $property;
}
