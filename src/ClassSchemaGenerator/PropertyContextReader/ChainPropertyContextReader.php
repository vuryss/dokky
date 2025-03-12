<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator\PropertyContextReader;

use Dokky\ClassSchemaGenerator\PropertyContext;
use Dokky\ClassSchemaGenerator\PropertyContextReaderInterface;

readonly class ChainPropertyContextReader implements PropertyContextReaderInterface
{
    /**
     * @param PropertyContextReaderInterface[] $readers
     */
    public function __construct(
        private array $readers,
    ) {
    }

    public function extract(\ReflectionProperty $property): PropertyContext
    {
        $groups = [];
        $ignored = false;
        $name = null;
        $minLength = null;
        $maxLength = null;
        $minItems = null;
        $maxItems = null;
        $minimum = null;
        $maximum = null;
        $exclusiveMinimum = null;
        $exclusiveMaximum = null;

        foreach ($this->readers as $reader) {
            $context = $reader->extract($property);

            $groups = [] === $groups ? $context->groups : $groups;
            $ignored = $ignored || $context->ignored;
            $name ??= $context->name;
            $minLength ??= $context->minLength;
            $maxLength ??= $context->maxLength;
            $minItems ??= $context->minItems;
            $maxItems ??= $context->maxItems;
            $minimum ??= $context->minimum;
            $maximum ??= $context->maximum;
            $exclusiveMinimum ??= $context->exclusiveMinimum;
            $exclusiveMaximum ??= $context->exclusiveMaximum;
        }

        return new PropertyContext(
            groups: $groups,
            ignored: $ignored,
            name: $name,
            minLength: $minLength,
            maxLength: $maxLength,
            minItems: $minItems,
            maxItems: $maxItems,
            minimum: $minimum,
            maximum: $maximum,
            exclusiveMinimum: $exclusiveMinimum,
            exclusiveMaximum: $exclusiveMaximum,
        );
    }
}
