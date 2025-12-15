<?php

declare(strict_types=1);

namespace Dokky;

readonly class ReflectionUtil
{
    /**
     * @template T of object
     *
     * @phpstan-param class-string<T>|T $classNameOrObject
     *
     * @return \ReflectionClass<T>
     *
     * @throws DokkyException
     */
    public static function reflectionClass(string|object $classNameOrObject): \ReflectionClass
    {
        try {
            return new \ReflectionClass($classNameOrObject);
        } catch (\ReflectionException $e) { // @phpstan-ignore catch.neverThrown
            throw new DokkyException(
                sprintf(
                    'Class "%s" not found. Make sure the class exists and is able to be autoloaded.',
                    is_string($classNameOrObject) ? $classNameOrObject : get_class($classNameOrObject),
                ),
                previous: $e
            );
        }
    }
}
