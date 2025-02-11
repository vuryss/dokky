<?php

declare(strict_types=1);

namespace Dokky\OpenApi\Schema;

enum Type: string
{
    case STRING = 'string';
    case NUMBER = 'number';
    case INTEGER = 'integer';
    case OBJECT = 'object';
    case ARRAY = 'array';
    case BOOLEAN = 'boolean';
    case NULL = 'null';
}
