<?php

test('Schema generator', function (string $className, Dokky\OpenApi\Schema $expectedSchema) {
    $schema = cleanObject(new Dokky\ClassSchemaGenerator()->generate($className));
    $expectedSchema = cleanObject($expectedSchema);

    // if ($className === \Dokky\Tests\Datasets\Classes\Variant1::class) {
    //     dump($schema);
    //     exit;
    // }

    expect($schema)
        ->toBeValidJsonSchema()
        ->toEqual($expectedSchema);
})->with('class-schemas');
