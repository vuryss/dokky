<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

enum In: string
{
    case QUERY = 'query';
    case HEADER = 'header';
    case PATH = 'path';
    case COOKIE = 'cookie';
}
