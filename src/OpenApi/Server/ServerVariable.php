<?php

declare(strict_types=1);

namespace Dokky\OpenApi\Server;

class ServerVariable
{
    /**
     * @param array<string> $enum
     */
    public function __construct(
        private array $enum,
        private string $default,
        private ?string $description = null,
    ) {
    }

    /**
     * @return array<string>
     */
    public function getEnum(): array
    {
        return $this->enum;
    }

    /**
     * @param array<string> $enum
     */
    public function setEnum(array $enum): self
    {
        $this->enum = $enum;

        return $this;
    }

    public function getDefault(): string
    {
        return $this->default;
    }

    public function setDefault(string $default): self
    {
        $this->default = $default;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
