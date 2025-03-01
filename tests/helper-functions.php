<?php

declare(strict_types=1);

function ddd(mixed $value): never {
    dump($value);
    exit;
}

function cleanObject(object $object): object {
    $json = (new \Vuryss\Serializer\Serializer())->serialize(
        $object,
        [\Vuryss\Serializer\SerializerInterface::ATTRIBUTE_SKIP_NULL_VALUES => true]
    );

    return (object) json_decode(
        $json,
        associative: false,
        flags: JSON_THROW_ON_ERROR
    );
}
