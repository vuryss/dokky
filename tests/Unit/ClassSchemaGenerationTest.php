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
            ->toThrow(Dokky\DokkyException::class);
    },
);

test(
    'Associative array with invalid key type is not supported',
    function () {
        $classSchemaGenerator = new Dokky\ClassSchemaGenerator\ClassSchemaGenerator();

        expect(fn () => $classSchemaGenerator->generate(Dokky\Tests\Datasets\Classes\InvalidVariant7::class))
            ->toThrow(Dokky\DokkyException::class);
    },
);
