<?php

declare(strict_types=1);

use Dokky\OpenApi\Schema;
use Dokky\OpenApi\Schema\Type;

dataset('class-schemas', [
    [
        'className' => Dokky\Tests\Datasets\Classes\Basic::class,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'someStringProperty' => new Schema(type: Type::STRING),
                'nullableIntProperty' => new Schema(
                    anyOf: [
                        new Schema(type: Type::INTEGER),
                        new Schema(type: Type::NULL),
                    ]
                ),
                'stringWithDefaultValue' => new Schema(
                    default: 'some default value',
                    anyOf: [
                        new Schema(type: Type::STRING),
                        new Schema(type: Type::NULL),
                    ],
                ),
                'floatProperty' => new Schema(type: Type::NUMBER, format: 'float'),
                'nullProperty' => new Schema(type: Type::NULL),
                'falseProperty' => new Schema(type: Type::BOOLEAN, enum: [false]),
                'trueProperty' => new Schema(type: Type::BOOLEAN, enum: [true]),
            ],
            required: [
                'someStringProperty',
                'nullableIntProperty',
                'floatProperty',
                'nullProperty',
                'falseProperty',
                'trueProperty',
            ],
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\MultiType::class,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'property' => new Schema(
                    anyOf: [
                        new Schema(type: Type::STRING),
                        new Schema(type: Type::INTEGER),
                        new Schema(type: Type::NULL),
                    ]
                ),
            ],
            required: ['property'],
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\Variant1::class,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'property' => new Schema(
                    default: 5,
                    anyOf: [
                        new Schema(ref: '#/components/schemas/Basic'),
                        new Schema(ref: '#/components/schemas/MultiType'),
                        new Schema(type: Type::INTEGER),
                        new Schema(type: Type::NULL),
                    ],
                ),
            ],
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithDateTime::class,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'property' => new Schema(type: Type::STRING, format: 'date-time'),
                'property2' => new Schema(type: Type::STRING, format: 'date-time'),
            ],
            required: ['property', 'property2'],
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithArrays::class,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'arrayOfScalar' => new Schema(type: Type::ARRAY, items: new Schema(type: Type::STRING)),
                'arrayOfObjects' => new Schema(
                    type: Type::ARRAY,
                    items: new Schema(
                        anyOf: [
                            new Schema(ref: '#/components/schemas/MultiType'),
                            new Schema(ref: '#/components/schemas/Basic'),
                        ]
                    )
                ),
                'arrayOfMultipleMixedTypes' => new Schema(
                    type: Type::ARRAY,
                    items: new Schema(
                        anyOf: [
                            new Schema(ref: '#/components/schemas/Basic'),
                            new Schema(ref: '#/components/schemas/MultiType'),
                            new Schema(type: Type::INTEGER),
                        ]
                    )
                ),
            ],
            required: ['arrayOfScalar', 'arrayOfObjects', 'arrayOfMultipleMixedTypes'],
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\Variant2::class,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'property' => new Schema(
                    default: null,
                    anyOf: [
                        new Schema(type: Type::STRING),
                        new Schema(type: Type::NULL),
                    ],
                ),
            ],
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithEnums::class,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'property' => new Schema(ref: '#/components/schemas/SomeStringBackedEnum'),
                'property2' => new Schema(ref: '#/components/schemas/SomeIntBackedEnum'),
            ],
            required: ['property', 'property2'],
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\SomeStringBackedEnum::class,
        'expectedSchema' => new Schema(
            type: Type::STRING,
            enum: ['A', 'B', 'C'],
        ),
    ],
]);
