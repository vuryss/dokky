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
