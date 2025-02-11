<?php

use Dokky\Tests\Datasets\Classes\Basic;

test('Basic class', function (string $className, string $schema) {
    $classSchemaGenerator = new \Dokky\ClassSchemaGenerator();
    $result = $classSchemaGenerator->generate(Basic::class);

    $expectedSchema = json_decode($schema, associative: false, flags: JSON_THROW_ON_ERROR | JSON_FORCE_OBJECT);

    expect($result)
        ->convertToObjectWithoutNulls()
        ->toBeValidJsonSchema()
        ->toEqual($expectedSchema);

})->with('class-schemas');
