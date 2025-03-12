<?php

declare(strict_types=1);

test(
    'Groups attribute should be used only once on a property',
    function () {
        new Dokky\ClassSchemaGenerator\ClassSchemaGenerator()
            ->generate(Dokky\Tests\Datasets\Classes\InvalidVariant1::class);
    }
)
->throws(
    Dokky\DokkyException::class,
    'Property "property1" of class "Dokky\Tests\Datasets\Classes\InvalidVariant1" has multiple "Dokky\Attribute\Groups" attributes, only one is allowed'
);

test(
    'Constraint attribute should be used only once on a property',
    function () {
        new Dokky\ClassSchemaGenerator\ClassSchemaGenerator()
            ->generate(Dokky\Tests\Datasets\Classes\InvalidVariant2::class);
    }
)
->throws(
    Dokky\DokkyException::class,
    'Property "property1" of class "Dokky\Tests\Datasets\Classes\InvalidVariant2" has multiple "Dokky\Attribute\Constraints" attributes, only one is allowed'
);

test(
    'Symfony Groups attribute should be used only once on a property',
    function () {
        new Dokky\ClassSchemaGenerator\ClassSchemaGenerator()
            ->generate(Dokky\Tests\Datasets\Classes\InvalidVariant3::class);
    }
)
->throws(
    Dokky\DokkyException::class,
    'Property "property1" of class "Dokky\Tests\Datasets\Classes\InvalidVariant3" has multiple "Symfony\Component\Serializer\Attribute\Groups" attributes, only one is allowed'
);

test(
    'Symfony Numeric constraints only support numbers now',
    function () {
        new Dokky\ClassSchemaGenerator\ClassSchemaGenerator()
            ->generate(Dokky\Tests\Datasets\Classes\InvalidVariant4::class);
    }
)
->throws(
    Dokky\DokkyException::class,
    'Expected numeric value, got: string'
);
