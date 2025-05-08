<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

class DataWithDescription
{
    /**
     * This is a description for property1.
     * It can be multi-line.
     *
     * @var string this should be ignored because of the description above
     */
    public string $property1;

    // This is a comment, not a description.
    public int $property2;

    /**
     * @see https://example.com This should be ignored
     */
    public bool $property3;

    /**
     * @var float this should be included in the description if no other description is provided
     */
    public float $property4;
}
