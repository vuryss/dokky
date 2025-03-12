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
        $context = new PropertyContext();

        foreach ($this->readers as $reader) {
            $extractedContext = $reader->extract($property);

            if ([] === $context->groups && [] !== $extractedContext->groups) {
                $context = $context->withGroups($extractedContext->groups);
            }

            if (!$context->ignored && $extractedContext->ignored) {
                $context = $context->withIgnored($extractedContext->ignored);
            }

            if (null === $context->name && null !== $extractedContext->name) {
                $context = $context->withName($extractedContext->name);
            }
        }

        return $context;
    }
}
