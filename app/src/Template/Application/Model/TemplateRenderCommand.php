<?php

declare(strict_types=1);

namespace App\Template\Application\Model;

final class TemplateRenderCommand
{
    private string $slug;

    private array $additionalVariables;

    public function __construct(string $slug, array $additionalVariables)
    {
        $this->slug = $slug;
        $this->additionalVariables = $additionalVariables;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getAdditionalVariables(): array
    {
        return $this->additionalVariables;
    }


}
