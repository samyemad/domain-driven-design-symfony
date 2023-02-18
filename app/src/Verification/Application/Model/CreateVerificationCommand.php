<?php

declare(strict_types=1);

namespace App\Verification\Application\Model;

final class CreateVerificationCommand
{
    private string $identity;

    private string $identityType;

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function setIdentity(string $identity): void
    {
        $this->identity = $identity;
    }

    public function getIdentityType(): string
    {
        return $this->identityType;
    }

    public function setIdentityType(string $identityType): void
    {
        $this->identityType = $identityType;
    }
}
