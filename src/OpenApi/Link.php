<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class Link implements \JsonSerializable
{
    use JsonSerializableTrait;

    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        public Undefined|string $operationRef = Undefined::VALUE,
        public Undefined|string $operationId = Undefined::VALUE,
        public Undefined|array $parameters = Undefined::VALUE,
        public mixed $requestBody = Undefined::VALUE,
        public Undefined|string $description = Undefined::VALUE,
        public Undefined|Server $server = Undefined::VALUE,
    ) {
    }
}
