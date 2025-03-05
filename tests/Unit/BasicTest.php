<?php

test('Schema generator', function (string $className, Dokky\OpenApi\Schema $expectedSchema) {
    $schema = cleanObject(new Dokky\ClassSchemaGenerator()->generate($className));
    $expectedSchema = cleanObject($expectedSchema);

    expect($schema)
        ->toBeValidJsonSchema()
        ->toEqual($expectedSchema);
})->with('class-schemas');
