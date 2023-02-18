<?php

declare(strict_types=1);

namespace App\Template\Domain\Service\Interfaces;

use Symfony\Component\HttpFoundation\Response;

interface TemplateResponseRenderInterface
{
    public function process(array $parameters, string $slug): Response;
}
