<?php

declare(strict_types=1);

use Dokky\OpenApi\Schema;
use Dokky\OpenApi\Schema\Type;

dataset('class-schemas', [
    [
        'className' => Dokky\Tests\Datasets\Classes\Basic::class,
        'groups' => null,
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
        'groups' => null,
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
        'groups' => null,
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
        'groups' => null,
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
        'groups' => null,
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
        'groups' => null,
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
        'groups' => null,
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
        'groups' => null,
        'expectedSchema' => new Schema(
            type: Type::STRING,
            enum: ['A', 'B', 'C'],
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithGroups::class,
        'groups' => null,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'property1' => new Schema(type: Type::STRING),
                'property2' => new Schema(type: Type::STRING),
                'property3' => new Schema(type: Type::STRING),
                'property4' => new Schema(type: Type::STRING),
                'property5' => new Schema(type: Type::STRING),
                'property11' => new Schema(type: Type::STRING),
                'property12' => new Schema(type: Type::STRING),
                'property13' => new Schema(type: Type::STRING),
                'property14' => new Schema(type: Type::STRING),
                'property15' => new Schema(type: Type::STRING),
                'property6' => new Schema(type: Type::STRING),
                'property7' => new Schema(type: Type::STRING),
                'property8' => new Schema(type: Type::STRING),
                'property9' => new Schema(type: Type::STRING),
                'property10' => new Schema(type: Type::STRING),
                'property16' => new Schema(type: Type::STRING),
                'property17' => new Schema(type: Type::STRING),
                'property18' => new Schema(type: Type::STRING),
                'property19' => new Schema(type: Type::STRING),
                'property20' => new Schema(type: Type::STRING),
            ],
            required: [
                'property1',
                'property2',
                'property3',
                'property4',
                'property5',
                'property11',
                'property12',
                'property13',
                'property14',
                'property15',
                'property6',
                'property7',
                'property8',
                'property9',
                'property10',
                'property16',
                'property17',
                'property18',
                'property19',
                'property20',
            ],
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithGroups::class,
        'groups' => ['group1'],
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'property1' => new Schema(type: Type::STRING),
                'property3' => new Schema(type: Type::STRING),
                'property4' => new Schema(type: Type::STRING),
                'property11' => new Schema(type: Type::STRING),
                'property13' => new Schema(type: Type::STRING),
                'property14' => new Schema(type: Type::STRING),
                'property6' => new Schema(type: Type::STRING),
                'property8' => new Schema(type: Type::STRING),
                'property9' => new Schema(type: Type::STRING),
                'property16' => new Schema(type: Type::STRING),
                'property18' => new Schema(type: Type::STRING),
                'property19' => new Schema(type: Type::STRING),
            ],
            required: [
                'property1',
                'property3',
                'property4',
                'property11',
                'property13',
                'property14',
                'property6',
                'property8',
                'property9',
                'property16',
                'property18',
                'property19',
            ],
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithGroups::class,
        'groups' => ['group1', 'group2'],
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'property1' => new Schema(type: Type::STRING),
                'property2' => new Schema(type: Type::STRING),
                'property3' => new Schema(type: Type::STRING),
                'property4' => new Schema(type: Type::STRING),
                'property11' => new Schema(type: Type::STRING),
                'property12' => new Schema(type: Type::STRING),
                'property13' => new Schema(type: Type::STRING),
                'property14' => new Schema(type: Type::STRING),
                'property6' => new Schema(type: Type::STRING),
                'property7' => new Schema(type: Type::STRING),
                'property8' => new Schema(type: Type::STRING),
                'property9' => new Schema(type: Type::STRING),
                'property16' => new Schema(type: Type::STRING),
                'property17' => new Schema(type: Type::STRING),
                'property18' => new Schema(type: Type::STRING),
                'property19' => new Schema(type: Type::STRING),
            ],
            required: [
                'property1',
                'property2',
                'property3',
                'property4',
                'property11',
                'property12',
                'property13',
                'property14',
                'property6',
                'property7',
                'property8',
                'property9',
                'property16',
                'property17',
                'property18',
                'property19',
            ],
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithIgnore::class,
        'groups' => null,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'property3' => new Schema(type: Type::STRING),
                'property6' => new Schema(type: Type::STRING),
            ],
            required: ['property3', 'property6'],
        ),
    ],
]);
