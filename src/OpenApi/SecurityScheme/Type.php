<?php

declare(strict_types=1);

namespace Dokky\OpenApi\SecurityScheme;

enum Type: string
{
    case API_KEY = 'apiKey';
    case HTTP = 'http';
    case OAUTH2 = 'oauth2';
    case OPEN_ID_CONNECT = 'openIdConnect';
    case MUTUAL_TLS = 'mutualTLS';
}
