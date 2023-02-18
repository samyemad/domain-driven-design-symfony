<?php

declare(strict_types=1);

namespace App\Template\Application\Model;

final class TemplateRenderCommand
{
    private string $slug;

    private array $additionalVariables;

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getAdditionalVariables(): array
    {
        return $this->additionalVariables;
    }

    public function setAdditionalVariables(array $additionalVariables): void
    {
        $this->additionalVariables = $additionalVariables;
    }
}
