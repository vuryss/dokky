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
                'objectProperty' => new Schema(type: Type::OBJECT),
            ],
            required: [
                'someStringProperty',
                'nullableIntProperty',
                'floatProperty',
                'nullProperty',
                'falseProperty',
                'trueProperty',
                'objectProperty',
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
                'arrayWithStringKeys' => new Schema(
                    type: Type::OBJECT,
                    additionalProperties: new Schema(ref: '#/components/schemas/Basic'),
                ),
                'arrayWithIntKeys' => new Schema(
                    type: Type::OBJECT,
                    additionalProperties: new Schema(ref: '#/components/schemas/Basic'),
                    propertyNames: new Schema(
                        type: Type::STRING,
                        pattern: '^[0-9]+$',
                    ),
                ),
                'alternateArrayDefinition' => new Schema(
                    type: Type::OBJECT,
                    additionalProperties: new Schema(ref: '#/components/schemas/Basic'),
                    propertyNames: new Schema(
                        type: Type::STRING,
                        pattern: '^[0-9]+$',
                    ),
                ),
            ],
            required: [
                'arrayOfScalar',
                'arrayOfObjects',
                'arrayOfMultipleMixedTypes',
                'arrayWithStringKeys',
                'arrayWithIntKeys',
                'alternateArrayDefinition',
            ],
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
        'className' => Dokky\Tests\Datasets\Classes\SomeIntBackedEnum::class,
        'groups' => null,
        'expectedSchema' => new Schema(
            type: Type::INTEGER,
            enum: [1, 2, 3],
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
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithSerializedName::class,
        'groups' => null,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'property_1' => new Schema(type: Type::STRING),
                'property_2' => new Schema(type: Type::STRING),
                'property3' => new Schema(type: Type::STRING),
                'property_4' => new Schema(type: Type::STRING),
                'property_5' => new Schema(type: Type::STRING),
                'property6' => new Schema(type: Type::STRING),
            ],
            required: [
                'property_1',
                'property_2',
                'property3',
                'property_4',
                'property_5',
                'property6',
            ],
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithConstraints::class,
        'groups' => null,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'property1' => new Schema(
                    type: Type::STRING,
                    minLength: 3,
                    maxLength: 7,
                ),
                'property2' => new Schema(
                    type: Type::STRING,
                    minLength: 3,
                    maxLength: 7,
                ),
                'property3' => new Schema(
                    type: Type::STRING,
                    minLength: 5,
                    maxLength: 5,
                ),
                'property4' => new Schema(
                    type: Type::ARRAY,
                    items: new Schema(type: Type::STRING),
                    minItems: 3,
                    maxItems: 7,
                ),
                'property5' => new Schema(
                    type: Type::ARRAY,
                    items: new Schema(type: Type::INTEGER),
                    minItems: 3,
                    maxItems: 7,
                ),
                'property6' => new Schema(
                    type: Type::ARRAY,
                    items: new Schema(type: Type::BOOLEAN),
                    minItems: 5,
                    maxItems: 5,
                ),
                'property7' => new Schema(
                    type: Type::INTEGER,
                    minimum: 3,
                    maximum: 7,
                ),
                'property8' => new Schema(
                    type: Type::NUMBER,
                    format: 'float',
                    minimum: 3.7,
                    maximum: 7.3,
                ),
                'property9' => new Schema(
                    type: Type::INTEGER,
                    exclusiveMinimum: 3,
                    exclusiveMaximum: 7,
                ),
                'property10' => new Schema(
                    type: Type::NUMBER,
                    format: 'float',
                    exclusiveMinimum: 3.7,
                    exclusiveMaximum: 7.3,
                ),
                'property11' => new Schema(
                    type: Type::INTEGER,
                    minimum: 3,
                    maximum: 7,
                ),
                'property12' => new Schema(
                    type: Type::NUMBER,
                    format: 'float',
                    minimum: 3.7,
                    maximum: 7.3,
                ),
                'property13' => new Schema(
                    type: Type::INTEGER,
                    exclusiveMaximum: 7,
                ),
                'property14' => new Schema(
                    type: Type::NUMBER,
                    format: 'float',
                    exclusiveMaximum: 7.3,
                ),
                'property15' => new Schema(
                    type: Type::INTEGER,
                    maximum: 7,
                ),
                'property16' => new Schema(
                    type: Type::NUMBER,
                    format: 'float',
                    maximum: 7.3,
                ),
                'property17' => new Schema(
                    type: Type::INTEGER,
                    exclusiveMinimum: 7,
                ),
                'property18' => new Schema(
                    type: Type::NUMBER,
                    format: 'float',
                    exclusiveMinimum: 7.3,
                ),
                'property19' => new Schema(
                    type: Type::INTEGER,
                    minimum: 7,
                ),
                'property20' => new Schema(
                    type: Type::NUMBER,
                    format: 'float',
                    minimum: 7.3,
                ),
            ],
            required: [
                'property1',
                'property2',
                'property3',
                'property4',
                'property5',
                'property6',
                'property7',
                'property8',
                'property9',
                'property10',
                'property11',
                'property12',
                'property13',
                'property14',
                'property15',
                'property16',
                'property17',
                'property18',
                'property19',
                'property20',
            ],
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithDiscriminatorMap::class,
        'groups' => null,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            anyOf: [
                new Schema(ref: '#/components/schemas/DataWithEnums'),
                new Schema(ref: '#/components/schemas/Basic'),
            ],
            discriminator: new Dokky\OpenApi\Discriminator(
                propertyName: 'type',
                mapping: [
                    'type1' => '#/components/schemas/DataWithEnums',
                    'type2' => '#/components/schemas/Basic',
                ],
            )
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithDiscriminatorMap2::class,
        'groups' => null,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            anyOf: [
                new Schema(ref: '#/components/schemas/DataWithEnums'),
                new Schema(ref: '#/components/schemas/Basic'),
            ],
            discriminator: new Dokky\OpenApi\Discriminator(
                propertyName: 'type',
                mapping: [
                    'type1' => '#/components/schemas/DataWithEnums',
                    'type2' => '#/components/schemas/Basic',
                ],
            )
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithDiscriminatorMap3::class,
        'groups' => null,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            anyOf: [
                new Schema(ref: '#/components/schemas/DataWithEnums'),
                new Schema(ref: '#/components/schemas/Basic'),
            ],
            discriminator: new Dokky\OpenApi\Discriminator(
                propertyName: 'type',
                mapping: [
                    'type1' => '#/components/schemas/DataWithEnums',
                    'type2' => '#/components/schemas/Basic',
                ],
            )
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithDiscriminatorMap4::class,
        'groups' => null,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            anyOf: [
                new Schema(ref: '#/components/schemas/DataWithEnums'),
                new Schema(ref: '#/components/schemas/Basic'),
            ],
            discriminator: new Dokky\OpenApi\Discriminator(
                propertyName: 'type',
                mapping: [
                    'type1' => '#/components/schemas/DataWithEnums',
                    'type2' => '#/components/schemas/Basic',
                ],
            )
        ),
    ],
    [
        'className' => Dokky\Tests\Datasets\Classes\DataWithSchemaOverwrite::class,
        'groups' => null,
        'expectedSchema' => new Schema(
            type: Type::OBJECT,
            properties: [
                'property' => new Schema(
                    type: Type::STRING,
                    description: 'Some description',
                    examples: ['test1', 'test2'],
                    enum: ['test1', 'test2', 'test3'],
                ),
            ],
            required: ['property'],
        ),
    ],
]);
