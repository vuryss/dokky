<?php

declare(strict_types=1);

namespace Dokky\ClassSchemaGenerator;

readonly class PropertyContext
{
    /**
     * @param array<string> $groups
     */
    public function __construct(
        public array $groups = [],
        public bool $ignored = false,
        public ?string $name = null,
    ) {
    }

    /**
     * @param array<string> $groups
     */
    public function withGroups(array $groups): self
    {
        return new self(
            groups: $groups,
            ignored: $this->ignored,
            name: $this->name,
        );
    }

    public function withIgnored(bool $ignored): self
    {
        return new self(
            groups: $this->groups,
            ignored: $ignored,
            name: $this->name,
        );
    }

    public function withName(string $name): self
    {
        return new self(
            groups: $this->groups,
            ignored: $this->ignored,
            name: $name,
        );
    }
}
