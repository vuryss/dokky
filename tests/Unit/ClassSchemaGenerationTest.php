<?php

test(
    'Can generate Json Schema from Class',

    /**
     * @param class-string  $className
     * @param array<string> $groups
     */
    function (string $className, ?array $groups, Dokky\OpenApi\Schema $expectedSchema) {
        $schema = cleanObject(new Dokky\ClassSchemaGenerator\ClassSchemaGenerator()->generate($className, $groups));
        $expectedSchema = cleanObject($expectedSchema);

        expect($schema)
            ->toBeValidJsonSchema()
            ->toEqual($expectedSchema);
    }
)->with('class-schemas');

test(
    'Can generate Json Schema from Class with default nullable configuration',

    /**
     * @param class-string  $className
     * @param array<string> $groups
     */
    function (string $className, ?array $groups, Dokky\OpenApi\Schema $expectedSchema) {
        $configuration = new Dokky\Configuration(
            considerNullablePropertiesAsNotRequired: true,
        );
        $schema = cleanObject(
            new Dokky\ClassSchemaGenerator\ClassSchemaGenerator(configuration: $configuration)
                ->generate($className, $groups)
        );
        $expectedSchema = cleanObject($expectedSchema);

        expect($schema)
            ->toBeValidJsonSchema()
            ->toEqual($expectedSchema);
    }
)->with('class-schemas-with-non-required-nullables');

test(
    'Cannot get schema for empty class without properties',
    function () {
        $classSchemaGenerator = new Dokky\ClassSchemaGenerator\ClassSchemaGenerator();

        expect(fn () => $classSchemaGenerator->generate(Dokky\Tests\Datasets\Classes\EmptyClass::class))
            ->toThrow(Dokky\DokkyException::class);
    },
);

test(
    'Cannot get schema for class with untyped properties',
    function () {
        $classSchemaGenerator = new Dokky\ClassSchemaGenerator\ClassSchemaGenerator();

        expect(fn () => $classSchemaGenerator->generate(Dokky\Tests\Datasets\Classes\UntypedProperty::class))
            ->toThrow(Dokky\DokkyException::class);
    },
);

test(
    'No discriminator map for abstract class or interface fails',
    function () {
        $classSchemaGenerator = new Dokky\ClassSchemaGenerator\ClassSchemaGenerator();

        expect(fn () => $classSchemaGenerator->generate(Dokky\Tests\Datasets\Classes\DataWithoutDiscriminatorMap::class))
            ->toThrow(Dokky\DokkyException::class);
    },
);

test(
    'Associative array with multiple key types is not supported',
    function () {
        $classSchemaGenerator = new Dokky\ClassSchemaGenerator\ClassSchemaGenerator();

        expect(fn () => $classSchemaGenerator->generate(Dokky\Tests\Datasets\Classes\InvalidVariant6::class))
            ->toThrow(
                Dokky\DokkyException::class,
                'Failed to extract type for property "multipleKeyTypes" in class "Dokky\Tests\Datasets\Classes\InvalidVariant6", because of the following error: "bool|int" is not a valid array key type.'
            );
    },
);

test(
    'Associative array with invalid key type is not supported',
    function () {
        $classSchemaGenerator = new Dokky\ClassSchemaGenerator\ClassSchemaGenerator();

        expect(fn () => $classSchemaGenerator->generate(Dokky\Tests\Datasets\Classes\InvalidVariant7::class))
            ->toThrow(
                Dokky\DokkyException::class,
                'Failed to extract type for property "invalidKeyType" in class "Dokky\Tests\Datasets\Classes\InvalidVariant7", because of the following error: "bool" is not a valid array key type.'
            );
    },
);

test(
    'Schema generation with nested groups',
    function () {
        $componentsRegistry = new Dokky\ComponentsRegistry();
        $classSchemaGenerator = new Dokky\ClassSchemaGenerator\ClassSchemaGenerator(componentsRegistry: $componentsRegistry);

        expect(
            cleanObject(
                $classSchemaGenerator->generate(Dokky\Tests\Datasets\Classes\Groups\RootObject::class, ['group1'])
            )
        )
            ->toBeValidJsonSchema()
            ->toEqual(
                cleanObject(
                    new Dokky\OpenApi\Schema(
                        type: Dokky\OpenApi\Schema\Type::OBJECT,
                        properties: [
                            'directProperty2' => new Dokky\OpenApi\Schema(type: Dokky\OpenApi\Schema\Type::STRING),
                            'nestedObject2' => new Dokky\OpenApi\Schema(ref: '#/components/schemas/NestedObject'),
                            'arrayOfNestedObjects2' => new Dokky\OpenApi\Schema(
                                type: Dokky\OpenApi\Schema\Type::ARRAY,
                                items: new Dokky\OpenApi\Schema(ref: '#/components/schemas/NestedObject'),
                            ),
                        ],
                        required: ['directProperty2', 'nestedObject2', 'arrayOfNestedObjects2'],
                    )
                )
            )
            ->and($componentsRegistry->getSchemaComponents())
            ->toContain([
                'className' => Dokky\Tests\Datasets\Classes\Groups\NestedObject::class,
                'groups' => ['default', 'group1'],
                'schemaName' => 'NestedObject',
            ]);
    }
);
