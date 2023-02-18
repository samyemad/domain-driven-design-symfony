<?php

declare(strict_types=1);

namespace App\Notification\Domain\Service\Interfaces;

interface SmsSenderProviderInterface
{
    public function process(string $body): int;
}
