<?php

declare(strict_types=1);

namespace App\Verification\Application\Model;

final class ConfirmVerificationCommand
{
    private string $verificationId;

    private string $code;

    public function __construct(string $verificationId,string $code)
    {
        $this->verificationId = $verificationId;
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
    public function getVerificationId(): string
    {
        return $this->verificationId;
    }
}
