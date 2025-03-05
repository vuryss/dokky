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
