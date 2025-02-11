<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class Operation
{
    /**
     * @param array<string> $tags
     * @param array<Parameter|Reference> $parameters
     * @param array<string, array<string, PathItem|Reference>|Reference>|null $callbacks
     * @param array<SecurityRequirement> $security
     * @param array<Server> $servers
     */
    public function __construct(
        private array $tags = [],
        private ?string $summary = null,
        private ?string $description = null,
        private ?ExternalDocumentation $externalDocs = null,
        private ?string $operationId = null,
        private array $parameters = [],
        private RequestBody|Reference|null $requestBody = null,
        private ?Responses $responses = null,
        private ?array $callbacks = null,
        private bool $deprecated = false,
        private array $security = [],
        private array $servers = [],
    ) {
    }

    /**
     * @return array<string>
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array<string> $tags
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;

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

    public function getExternalDocs(): ?ExternalDocumentation
    {
        return $this->externalDocs;
    }

    public function setExternalDocs(?ExternalDocumentation $externalDocs): self
    {
        $this->externalDocs = $externalDocs;

        return $this;
    }

    public function getOperationId(): ?string
    {
        return $this->operationId;
    }

    public function setOperationId(?string $operationId): self
    {
        $this->operationId = $operationId;

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

    public function getRequestBody(): Reference|RequestBody|null
    {
        return $this->requestBody;
    }

    public function setRequestBody(Reference|RequestBody|null $requestBody): self
    {
        $this->requestBody = $requestBody;

        return $this;
    }

    public function getResponses(): Responses
    {
        return $this->responses;
    }

    public function setResponses(Responses $responses): self
    {
        $this->responses = $responses;

        return $this;
    }

    /**
     * @return array<string, array<string, PathItem|Reference>|Reference>|null
     */
    public function getCallbacks(): ?array
    {
        return $this->callbacks;
    }

    /**
     * @param array<string, array<string, PathItem|Reference>|Reference>|null $callbacks
     */
    public function setCallbacks(?array $callbacks): self
    {
        $this->callbacks = $callbacks;

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

    /**
     * @return array<SecurityRequirement>
     */
    public function getSecurity(): array
    {
        return $this->security;
    }

    /**
     * @param array<SecurityRequirement> $security
     */
    public function setSecurity(array $security): self
    {
        $this->security = $security;

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
}
