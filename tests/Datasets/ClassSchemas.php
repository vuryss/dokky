<?php

declare(strict_types=1);

use Dokky\Tests\Datasets\Classes\Basic;

dataset('class-schemas', [
    ['className' => Basic::class, 'schema' => '{"required":["someStringProperty","nullableIntProperty","floatProperty"],"properties":{"someStringProperty":{"type":"string"},"nullableIntProperty":{"type":["integer","null"]},"stringWithDefaultValue":{"type":["string","null"],"default":"some default value"},"floatProperty":{"type":"number","format":"float"}},"type":"object"}'],
]);
