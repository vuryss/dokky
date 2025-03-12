<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator;

interface PropertyContextReaderInterface
{
    public function extract(\ReflectionProperty $property): PropertyContext;
}
