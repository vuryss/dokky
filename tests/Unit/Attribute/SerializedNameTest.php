<?php

declare(strict_types=1);

test(
    'SerializedName attribute should throw an exception if the serialized name is invalid',
    function ($serializedName) {
        new Dokky\Attribute\SerializedName($serializedName);
    },
)
->with([
    'empty' => '',
])
->throws(Dokky\DokkyException::class);
