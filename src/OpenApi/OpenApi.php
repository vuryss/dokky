<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class OpenApi
{
    /**
     * @param array<Server> $servers
     * @param array<string, PathItem> $paths
     */
    public function __construct(
        private string $openapi,
        private Info $info,
        private ?string $jsonSchemaDialect = null,
        private array $servers = [],
        private array $paths = [],
        private array $webhooks,
        private ?Components $components = null,
        private array $security,
        private array $tags,
        private ExternalDocumentation $externalDocs,
    ) {
    }

    public function getOpenapi(): string
    {
        return $this->openapi;
    }

    public function setOpenapi(string $openapi): void
    {
        $this->openapi = $openapi;
    }

    public function getInfo(): Info
    {
        return $this->info;
    }

    public function setInfo(Info $info): void
    {
        $this->info = $info;
    }

    public function getJsonSchemaDialect(): string
    {
        return $this->jsonSchemaDialect;
    }

    public function setJsonSchemaDialect(string $jsonSchemaDialect): void
    {
        $this->jsonSchemaDialect = $jsonSchemaDialect;
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
    public function setServers(array $servers): void
    {
        $this->servers = $servers;
    }

    public function getPaths(): array
    {
        return $this->paths;
    }

    public function setPaths(array $paths): void
    {
        $this->paths = $paths;
    }

    public function getWebhooks(): array
    {
        return $this->webhooks;
    }

    public function setWebhooks(array $webhooks): void
    {
        $this->webhooks = $webhooks;
    }

    public function getComponents(): Components
    {
        return $this->components;
    }

    public function setComponents(Components $components): void
    {
        $this->components = $components;
    }

    public function getSecurity(): array
    {
        return $this->security;
    }

    public function setSecurity(array $security): void
    {
        $this->security = $security;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    public function getExternalDocs(): ExternalDocumentation
    {
        return $this->externalDocs;
    }

    public function setExternalDocs(ExternalDocumentation $externalDocs): void
    {
        $this->externalDocs = $externalDocs;
    }
}
