<?php

declare(strict_types=1);

namespace App\Verification\Application\Model;

final class CreateVerificationCommand
{
    private string $identity;

    private string $identityType;

    public function __construct(string $identity,string $identityType)
    {
        $this->identity = $identity;
        $this->identityType = $identityType;
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function getIdentityType(): string
    {
        return $this->identityType;
    }
}
