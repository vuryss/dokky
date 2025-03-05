<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator;

readonly class Util
{
    /**
     * @param array<string> $groups
     *
     * @return array<string>
     */
    public static function formatGroups(array $groups): array
    {
        return array_unique(
            array_map(
                mb_strtolower(...),
                array_map(
                    trim(...),
                    $groups
                )
            ),
        );
    }
}
