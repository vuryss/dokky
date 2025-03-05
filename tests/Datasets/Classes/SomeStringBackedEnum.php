<?php

declare(strict_types=1);

namespace Dokky\Tests\Datasets\Classes;

enum SomeStringBackedEnum: string
{
    case A = 'A';
    case B = 'B';
    case C = 'C';
}
