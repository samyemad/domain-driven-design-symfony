<?php

declare(strict_types=1);

namespace App\Template\Domain\Entity;

use App\Shared\Aggregate\AggregateRoot;

class Template extends AggregateRoot
{
    private string $id;

    private Content $content;

    private string $slug;

    private \DateTimeImmutable $createdAt;

    public function __construct(TemplateId $id,string $slug, Content $content)
    {
        $this->id = $id->getValue();
        $this->slug = $slug;
        $this->content = $content;
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getId(): ?TemplateId
    {
        return new TemplateId($this->id);
    }

    public function getContent(): Content
    {
        return $this->content;
    }

    private function setContent(Content $content): void
    {
        $this->content = $content;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    private function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    private function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
