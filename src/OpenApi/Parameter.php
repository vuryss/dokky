<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class Parameter
{
    /**
     * @param array<string, Example|Reference>|null $examples
     * @param array<string, MediaType>|null $content
     */
    public function __construct(
        private string $name,
        private In $in,
        private ?string $description = null,
        private bool $required = false,
        private bool $deprecated = false,
        private bool $allowEmptyValue = false,
        private ?string $style = null,
        private ?string $explode = null,
        private bool $allowReserved = false,
        private ?Schema $schema = null,
        private mixed $example = null,
        private ?array $examples = null,
        private ?array $content = null,
    ) {
        if (In::PATH === $in) {
            $this->required = true;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIn(): In
    {
        return $this->in;
    }

    public function setIn(In $in): self
    {
        $this->in = $in;

        if (In::PATH === $in) {
            $this->required = true;
        }

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

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        if (In::PATH === $this->in) {
            $required = true;
        }

        $this->required = $required;

        return $this;
    }

    public function isDeprecated(): bool
    {
        return $this->deprecated;
    }

    public function setDeprecated(bool $deprecated): self
    {
        $this->deprecated = $deprecated;

        return $this;
    }

    public function isAllowEmptyValue(): bool
    {
        return $this->allowEmptyValue;
    }

    public function setAllowEmptyValue(bool $allowEmptyValue): self
    {
        $this->allowEmptyValue = $allowEmptyValue;

        return $this;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(?string $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getExplode(): ?string
    {
        return $this->explode;
    }

    public function setExplode(?string $explode): self
    {
        $this->explode = $explode;

        return $this;
    }

    public function isAllowReserved(): bool
    {
        return $this->allowReserved;
    }

    public function setAllowReserved(bool $allowReserved): self
    {
        $this->allowReserved = $allowReserved;

        return $this;
    }

    public function getSchema(): ?Schema
    {
        return $this->schema;
    }

    public function setSchema(?Schema $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    public function getExample(): mixed
    {
        return $this->example;
    }

    public function setExample(mixed $example): self
    {
        $this->example = $example;

        return $this;
    }

    /**
     * @return array<string, Example|Reference>|null
     */
    public function getExamples(): ?array
    {
        return $this->examples;
    }

    /**
     * @param array<string, Example|Reference>|null $examples
     */
    public function setExamples(?array $examples): self
    {
        $this->examples = $examples;

        return $this;
    }

    /**
     * @return array<string, MediaType>|null
     */
    public function getContent(): ?array
    {
        return $this->content;
    }

    /**
     * @param array<string, MediaType>|null $content
     */
    public function setContent(?array $content): self
    {
        $this->content = $content;

        return $this;
    }
}
