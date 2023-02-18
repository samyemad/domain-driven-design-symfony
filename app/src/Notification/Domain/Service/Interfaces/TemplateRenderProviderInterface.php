<?php

declare(strict_types=1);

namespace App\Notification\Domain\Service\Interfaces;

interface TemplateRenderProviderInterface
{
    public function process(string $channel, string $code): ?string;
}
