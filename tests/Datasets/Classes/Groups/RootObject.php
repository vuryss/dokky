<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes\Groups;

use Dokky\Attribute\Groups;

readonly class RootObject
{
    public string $directProperty;

    public NestedObject $nestedObject;

    /**
     * @var array<NestedObject>
     */
    public array $arrayOfNestedObjects;

    #[Groups(['group1'])]
    public string $directProperty2;

    #[Groups(['group1'])]
    public NestedObject $nestedObject2;

    /**
     * @var array<NestedObject>
     */
    #[Groups(['group1'])]
    public array $arrayOfNestedObjects2;
}
