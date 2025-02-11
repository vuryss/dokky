<?php

declare(strict_types=1);

namespace Dokky\Tests\Helpers;

use Vuryss\Serializer\SerializerInterface;

class Serializer
{
    private static SerializerInterface $serializer;

    private static function getSerializer(): SerializerInterface
    {
        if (!isset(self::$serializer)) {
            self::$serializer = new \Vuryss\Serializer\Serializer();
        }

        return self::$serializer;
    }

    public static function objectWithoutNulls(object $object): object
    {
        $json = self::getSerializer()->serialize(
            $object,
            [SerializerInterface::ATTRIBUTE_SKIP_NULL_VALUES => true]
        );

        return (object) json_decode(
            $json,
            associative: false,
            flags: JSON_THROW_ON_ERROR
        );
    }
}
