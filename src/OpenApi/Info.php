<?php

declare(strict_types=1);

namespace Dokky\OpenApi;

class Info
{
    public function __construct(
        private string $title,
        private string $version,
        private ?string $summary = null,
        private ?string $description = null,
        private ?string $termsOfService = null,
        private ?Info\Contact $contact = null,
        private ?Info\License $license = null,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

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

    public function getTermsOfService(): ?string
    {
        return $this->termsOfService;
    }

    public function setTermsOfService(?string $termsOfService): self
    {
        $this->termsOfService = $termsOfService;

        return $this;
    }

    public function getContact(): ?Info\Contact
    {
        return $this->contact;
    }

    public function setContact(?Info\Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getLicense(): ?Info\License
    {
        return $this->license;
    }

    public function setLicense(?Info\License $license): self
    {
        $this->license = $license;

        return $this;
    }
}
