<?php

declare(strict_types=1);

namespace Dokky\OpenApi\SecurityScheme;

enum In: string
{
    case QUERY = 'query';
    case HEADER = 'header';
    case COOKIE = 'cookie';
}
