<?php

test('Schema generator', function (string $className, \Dokky\OpenApi\Schema $expectedSchema) {
    $expectedSchema = json_decode(json_encode($expectedSchema));

    expect(new \Dokky\ClassSchemaGenerator()->generate($className))
        ->convertToObjectWithoutNulls()
        ->toBeValidJsonSchema()
        ->toEqual($expectedSchema);
})->with('class-schemas');
