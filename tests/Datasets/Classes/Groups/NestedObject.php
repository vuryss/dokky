<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes\Groups;

use Dokky\Attribute\Groups;

readonly class NestedObject
{
    public string $noGroup;

    #[Groups(['group1'])]
    public string $group1;
}
