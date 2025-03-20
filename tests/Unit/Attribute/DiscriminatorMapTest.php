<?php

declare(strict_types=1);

test(
    'Cannot use DiscriminatorMap attribute without type property',
    function (): void {
        new Dokky\Attribute\DiscriminatorMap('', ['test' => DateTime::class]);
    },
)->throws(Dokky\DokkyException::class);

test(
    'Cannot use DiscriminatorMap attribute with empty map',
    function (): void {
        new Dokky\Attribute\DiscriminatorMap('test', []);
    },
)->throws(Dokky\DokkyException::class);
