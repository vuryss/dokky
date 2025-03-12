<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

use Dokky\Attribute\Constraints;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Range;

class DataWithConstraints
{
    // String length

    #[Constraints(minLength: 3, maxLength: 7)]
    public string $property1;

    #[Length(min: 3, max: 7)]
    public string $property2;

    #[Length(exactly: 5)]
    public string $property3;

    // Array length

    /**
     * @var array<string>
     */
    #[Constraints(minItems: 3, maxItems: 7)]
    public array $property4;

    /**
     * @var array<int>
     */
    #[Count(min: 3, max: 7)]
    public array $property5;

    /**
     * @var array<bool>
     */
    #[Count(exactly: 5)]
    public array $property6;

    // Number range

    #[Constraints(minimum: 3, maximum: 7)]
    public int $property7;

    #[Constraints(minimum: 3.7, maximum: 7.3)]
    public float $property8;

    #[Constraints(exclusiveMinimum: 3, exclusiveMaximum: 7)]
    public int $property9;

    #[Constraints(exclusiveMinimum: 3.7, exclusiveMaximum: 7.3)]
    public float $property10;

    #[Range(min: 3, max: 7)]
    public int $property11;

    #[Range(min: 3.7, max: 7.3)]
    public float $property12;

    #[LessThan(value: 7)]
    public int $property13;

    #[LessThan(value: 7.3)]
    public float $property14;

    #[LessThanOrEqual(value: 7)]
    public int $property15;

    #[LessThanOrEqual(value: 7.3)]
    public float $property16;

    #[GreaterThan(value: 7)]
    public int $property17;

    #[GreaterThan(value: 7.3)]
    public float $property18;

    #[GreaterThanOrEqual(value: 7)]
    public int $property19;

    #[GreaterThanOrEqual(value: 7.3)]
    public float $property20;
}
