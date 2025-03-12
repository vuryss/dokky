<?php

declare(strict_types=1);

test(
    'Cannot create groups attribute with invalid groups',
    function ($invalidGroup) {
        new Dokky\Attribute\Groups($invalidGroup);
    }
)
->with([
    'empty' => [[]],
    'non-string' => [[1, 2, 3]],
    'empty-string' => [['']],
])
->throws(
    Dokky\DokkyException::class,
);
