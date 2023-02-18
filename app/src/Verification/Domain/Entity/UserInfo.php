<?php

declare(strict_types=1);

namespace App\Verification\Domain\Entity;

final class UserInfo
{
    private string $clientIp;

    private string $userAgent;

    public function __construct(string $clientIp, string $userAgent)
    {
        $this->clientIp = $clientIp;
        $this->userAgent = $userAgent;
    }

    public function getClientIp(): string
    {
        return $this->clientIp;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }
}
