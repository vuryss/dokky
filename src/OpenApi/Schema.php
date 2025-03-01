<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\OpenApi\Schema\Type;
use Dokky\Undefined;

class Schema implements \JsonSerializable
{
    /**
     * @param Type|array<Type>|Undefined $type
     * @param array<string, Schema>|Undefined $properties
     * @param array<string, Schema>|Undefined $patternProperties
     * @param array<string>|Undefined $required
     * @param array<Schema>|Undefined $prefixItems
     * @param array<Schema>|Undefined $anyOf
     * @param array<scalar>|Undefined $enum
     * @param array<string>|Undefined $examples
     */
    public function __construct(
        public Undefined|Type|array $type = Undefined::VALUE,
        public Undefined|string $title = Undefined::VALUE,
        public Undefined|string $description = Undefined::VALUE,
        public mixed $default = Undefined::VALUE,
        public Undefined|array $examples = Undefined::VALUE,
        public Undefined|bool $readOnly = Undefined::VALUE,
        public Undefined|bool $writeOnly = Undefined::VALUE,
        public Undefined|bool $deprecated = Undefined::VALUE,
        public Undefined|array $enum = Undefined::VALUE,
        public mixed $const = Undefined::VALUE,
        public Undefined|string $contentMediaType = Undefined::VALUE,
        public Undefined|string $contentEncoding = Undefined::VALUE,
        public Undefined|array $anyOf = Undefined::VALUE,
        public Undefined|string $ref = Undefined::VALUE,

        // string type
        public Undefined|int $minLength = Undefined::VALUE,
        public Undefined|int $maxLength = Undefined::VALUE,
        public Undefined|string $pattern = Undefined::VALUE,
        public Undefined|string $format = Undefined::VALUE,

        // number type
        public Undefined|int|float $multipleOf = Undefined::VALUE,
        public Undefined|int|float $minimum = Undefined::VALUE,
        public Undefined|int|float $exclusiveMinimum = Undefined::VALUE,
        public Undefined|int|float $maximum = Undefined::VALUE,
        public Undefined|int|float $exclusiveMaximum = Undefined::VALUE,

        // object type
        public Undefined|array $properties = Undefined::VALUE,
        public Undefined|array $patternProperties = Undefined::VALUE,
        public Undefined|false|self $additionalProperties = Undefined::VALUE,
        public Undefined|array $required = Undefined::VALUE,
        public Undefined|self $propertyNames = Undefined::VALUE,
        public Undefined|int $minProperties = Undefined::VALUE,
        public Undefined|int $maxProperties = Undefined::VALUE,
        public Undefined|self|false $unevaluatedProperties = Undefined::VALUE,

        // array type
        public Undefined|false|self $items = Undefined::VALUE,
        public Undefined|array $prefixItems = Undefined::VALUE,
        public Undefined|self|false $unevaluatedItems = Undefined::VALUE,
        public Undefined|self $contains = Undefined::VALUE,
        public Undefined|int $minContains = Undefined::VALUE,
        public Undefined|int $maxContains = Undefined::VALUE,
        public Undefined|int $minItems = Undefined::VALUE,
        public Undefined|int $maxItems = Undefined::VALUE,
        public Undefined|bool $uniqueItems = Undefined::VALUE,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        $data = array_filter(get_object_vars($this), static fn ($value) => Undefined::VALUE !== $value);

        if (isset($data['ref'])) {
            $data['$ref'] = $data['ref'];
            unset($data['ref']);
        }

        return $data;
    }
}
