<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class Components
{
    /**
     * @param array<string, Schema>|null                                      $schemas
     * @param array<string, Response|Reference>|null                          $responses
     * @param array<string, Parameter|Reference>|null                         $parameters
     * @param array<string, Example|Reference>|null                           $examples
     * @param array<string, RequestBody|Reference>|null                       $requestBodies
     * @param array<string, Header|Reference>|null                            $headers
     * @param array<string, SecurityScheme|Reference>|null                    $securitySchemes
     * @param array<string, Link|Reference>|null                              $links
     * @param array<string, array<string, PathItem|Reference>|Reference>|null $callbacks
     * @param array<string, PathItem|Reference>|null                          $pathItems
     */
    public function __construct(
        private ?array $schemas = null,
        private ?array $responses = null,
        private ?array $parameters = null,
        private ?array $examples = null,
        private ?array $requestBodies = null,
        private ?array $headers = null,
        private ?array $securitySchemes = null,
        private ?array $links = null,
        private ?array $callbacks = null,
        private ?array $pathItems = null,
    ) {
    }

    /**
     * @return array<string, Schema>|null
     */
    public function getSchemas(): ?array
    {
        return $this->schemas;
    }

    /**
     * @param array<string, Schema>|null $schemas
     */
    public function setSchemas(?array $schemas): self
    {
        $this->schemas = $schemas;

        return $this;
    }

    /**
     * @return array<string, Response|Reference>|null
     */
    public function getResponses(): ?array
    {
        return $this->responses;
    }

    /**
     * @param array<string, Response|Reference>|null $responses
     */
    public function setResponses(?array $responses): self
    {
        $this->responses = $responses;

        return $this;
    }

    /**
     * @return array<string, Parameter|Reference>|null
     */
    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    /**
     * @param array<string, Parameter|Reference>|null $parameters
     */
    public function setParameters(?array $parameters): self
    {
        $this->parameters = $parameters;

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
     * @return array<string, RequestBody|Reference>|null
     */
    public function getRequestBodies(): ?array
    {
        return $this->requestBodies;
    }

    /**
     * @param array<string, RequestBody|Reference>|null $requestBodies
     */
    public function setRequestBodies(?array $requestBodies): self
    {
        $this->requestBodies = $requestBodies;

        return $this;
    }

    /**
     * @return array<string, Header|Reference>|null
     */
    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    /**
     * @param array<string, Header|Reference>|null $headers
     */
    public function setHeaders(?array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array<string, SecurityScheme|Reference>|null
     */
    public function getSecuritySchemes(): ?array
    {
        return $this->securitySchemes;
    }

    /**
     * @param array<string, SecurityScheme|Reference>|null $securitySchemes
     */
    public function setSecuritySchemes(?array $securitySchemes): self
    {
        $this->securitySchemes = $securitySchemes;

        return $this;
    }

    /**
     * @return array<string, Link|Reference>|null
     */
    public function getLinks(): ?array
    {
        return $this->links;
    }

    /**
     * @param array<string, Link|Reference>|null $links
     */
    public function setLinks(?array $links): self
    {
        $this->links = $links;

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

    /**
     * @return array<string, PathItem|Reference>|null
     */
    public function getPathItems(): ?array
    {
        return $this->pathItems;
    }

    /**
     * @param array<string, PathItem|Reference>|null $pathItems
     */
    public function setPathItems(?array $pathItems): self
    {
        $this->pathItems = $pathItems;

        return $this;
    }
}
