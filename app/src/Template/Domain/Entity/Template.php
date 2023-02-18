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

    public function __construct(TemplateId $id)
    {
        $this->id = $id->getValue();
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

    public function create(string $slug, Content $content): self
    {
        $this->setSlug($slug);
        $this->setContent($content);
        $this->setCreatedAt(new \DateTimeImmutable('now'));

        return $this;
    }
}
