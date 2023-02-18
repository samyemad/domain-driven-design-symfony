<?php

declare(strict_types=1);

namespace App\Notification\Domain\Service\Interfaces;

interface EmailSenderProviderInterface
{
    public function process(string $body, string $recipient): void;
}
