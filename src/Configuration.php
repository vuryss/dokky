<?php

declare(strict_types=1);

namespace Dokky;

readonly class Configuration
{
    /**
     * @param bool $considerNullablePropertiesAsNotRequired when set to true, nullable properties will be considered as
     *                                                      not required in the OpenAPI schema generation
     */
    public function __construct(
        public bool $considerNullablePropertiesAsNotRequired = false,
    ) {
    }
}
