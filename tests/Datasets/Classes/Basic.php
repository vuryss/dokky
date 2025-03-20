<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

class Basic
{
    public string $someStringProperty;

    public ?int $nullableIntProperty;

    public ?string $stringWithDefaultValue = 'some default value';

    public float $floatProperty;

    public null $nullProperty;

    public false $falseProperty;

    public true $trueProperty;

    public object $objectProperty;
}
