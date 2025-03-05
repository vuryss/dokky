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
        $data = array_filter(get_object_vars($this), static fn ($value) => Undefined::VALUE !== $value);

        if (isset($data['ref'])) {
            $data['$ref'] = $data['ref'];
            unset($data['ref']);
        }

        return $data;
    }
}
