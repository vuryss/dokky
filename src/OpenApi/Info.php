<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

class Info implements \JsonSerializable
{
    use JsonSerializableTrait;

    public function __construct(
        public string $title,
        public string $version,
        public Undefined|string $summary = Undefined::VALUE,
        public Undefined|string $description = Undefined::VALUE,
        public Undefined|string $termsOfService = Undefined::VALUE,
        public Undefined|Info\Contact $contact = Undefined::VALUE,
        public Undefined|Info\License $license = Undefined::VALUE,
    ) {
    }
}
