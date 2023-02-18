<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Service;

use App\Notification\Domain\Service\Interfaces\TranslatorProviderInterface;

final class TranslatorProvider implements TranslatorProviderInterface
{
    /**
     * translate type  from verification service  into notification service.
     */
    public function translateChannel(string $identityType): string
    {
        return str_replace('_confirmation', '-verification', $identityType);
    }
}
