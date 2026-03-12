<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

use Dokky\Undefined;

trait JsonSerializableTrait
{
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        /** @var array<string, mixed> $data */
        $data = [];

        foreach (get_object_vars($this) as $property => $value) {
            if (Undefined::VALUE === $value) {
                continue;
            }

            $data[(string) $property] = $value;
        }

        if (isset($data['ref'])) {
            $data['$ref'] = $data['ref'];
            unset($data['ref']);
        }

        /** @var array<string, mixed> $result */
        $result = $data;

        return $result;
    }
}
