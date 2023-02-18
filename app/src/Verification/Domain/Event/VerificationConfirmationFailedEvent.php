<?php

declare(strict_types=1);

namespace App\Verification\Domain\Event;

use App\Shared\Event\DomainEventInterface;
use App\Verification\Domain\Entity\VerificationId;
use Symfony\Contracts\EventDispatcher\Event;

final class VerificationConfirmationFailedEvent extends Event implements DomainEventInterface
{
    protected \DateTimeImmutable $occur;

    protected VerificationId $verificationId;

    protected string $code;

    public function __construct(VerificationId $verificationId, string $code)
    {
        $this->verificationId = $verificationId;
        $this->code = $code;
    }

    public function getVerificationId(): VerificationId
    {
        return $this->verificationId;
    }

    public function getOccur(): \DateTimeImmutable
    {
        return $this->occur;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
