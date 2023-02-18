<?php

declare(strict_types=1);

namespace App\Notification\Domain\Service\Interfaces;

interface TranslatorProviderInterface
{
    public function translateChannel(string $identityType): string;
}
