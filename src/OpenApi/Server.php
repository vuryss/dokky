<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class Server
{
    /**
     * @param array<Server\ServerVariable> $variables
     */
    public function __construct(
        private string $url,
        private ?string $description = null,
        private array $variables = [],
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

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

    /**
     * @return array<Server\ServerVariable>
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param array<Server\ServerVariable> $variables
     */
    public function setVariables(array $variables): self
    {
        $this->variables = $variables;

        return $this;
    }
}
