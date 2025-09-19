<?php

declare(strict_types=1);

use Dokky\OpenApi\Schema;
use Dokky\OpenApi\Schema\Type;

dataset('class-schemas-with-non-required-nullables', [
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
                    ],
                ),
                'stringWithDefaultValue' => new Schema(
                    default: 'some default value',
                    anyOf: [
                        new Schema(type: Type::STRING),
                        new Schema(type: Type::NULL),
                    ],
                ),
                'floatProperty' => new Schema(type: Type::NUMBER, format: 'float'),
                'nullProperty' => new Schema(
                    type: Type::NULL,
                ),
                'falseProperty' => new Schema(type: Type::BOOLEAN, enum: [false]),
                'trueProperty' => new Schema(type: Type::BOOLEAN, enum: [true]),
                'objectProperty' => new Schema(type: Type::OBJECT),
            ],
            required: [
                'someStringProperty',
                'floatProperty',
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
                        new Schema(type: Type::INTEGER),
                        new Schema(type: Type::STRING),
                        new Schema(type: Type::NULL),
                    ],
                ),
            ],
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
]);
