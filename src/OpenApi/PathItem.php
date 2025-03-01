<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class PathItem
{
    /**
     * @param array<Server>              $servers
     * @param array<Parameter|Reference> $parameters
     */
    public function __construct(
        private ?string $ref = null,
        private ?string $summary = null,
        private ?string $description = null,
        private ?Operation $get = null,
        private ?Operation $put = null,
        private ?Operation $post = null,
        private ?Operation $delete = null,
        private ?Operation $options = null,
        private ?Operation $head = null,
        private ?Operation $patch = null,
        private ?Operation $trace = null,
        private array $servers = [],
        private array $parameters = [],
    ) {
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(?string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

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

    public function getGet(): ?Operation
    {
        return $this->get;
    }

    public function setGet(?Operation $get): self
    {
        $this->get = $get;

        return $this;
    }

    public function getPut(): ?Operation
    {
        return $this->put;
    }

    public function setPut(?Operation $put): self
    {
        $this->put = $put;

        return $this;
    }

    public function getPost(): ?Operation
    {
        return $this->post;
    }

    public function setPost(?Operation $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getDelete(): ?Operation
    {
        return $this->delete;
    }

    public function setDelete(?Operation $delete): self
    {
        $this->delete = $delete;

        return $this;
    }

    public function getOptions(): ?Operation
    {
        return $this->options;
    }

    public function setOptions(?Operation $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function getHead(): ?Operation
    {
        return $this->head;
    }

    public function setHead(?Operation $head): self
    {
        $this->head = $head;

        return $this;
    }

    public function getPatch(): ?Operation
    {
        return $this->patch;
    }

    public function setPatch(?Operation $patch): self
    {
        $this->patch = $patch;

        return $this;
    }

    public function getTrace(): ?Operation
    {
        return $this->trace;
    }

    public function setTrace(?Operation $trace): self
    {
        $this->trace = $trace;

        return $this;
    }

    /**
     * @return array<Server>
     */
    public function getServers(): array
    {
        return $this->servers;
    }

    /**
     * @param array<Server> $servers
     */
    public function setServers(array $servers): self
    {
        $this->servers = $servers;

        return $this;
    }

    /**
     * @return array<Parameter|Reference>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array<Parameter|Reference> $parameters
     */
    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;

        return $this;
    }
}
