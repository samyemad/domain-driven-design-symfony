<?php

declare(strict_types=1);

namespace App\Verification\Application\Model;

final class ConfirmVerificationCommand
{
    private string $verificationId;

    private string $code;

    public function __construct(string $verificationId)
    {
        $this->verificationId = $verificationId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getVerificationId(): string
    {
        return $this->verificationId;
    }

    public function setVerificationId(string $verificationId): void
    {
        $this->verificationId = $verificationId;
    }
}
