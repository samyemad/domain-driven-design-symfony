<?php

declare(strict_types=1);

namespace App\Verification\Domain\Event;

use App\Shared\Event\DomainEventInterface;
use App\Verification\Domain\Entity\Subject;
use App\Verification\Domain\Entity\VerificationId;
use Symfony\Contracts\EventDispatcher\Event;

final class VerificationCreatedEvent extends Event implements DomainEventInterface
{
    protected \DateTimeImmutable $occur;

    protected VerificationId $verificationId;

    protected Subject $subject;

    protected string $code;

    public function __construct(VerificationId $verificationId, string $code, Subject $subject)
    {
        $this->verificationId = $verificationId;
        $this->subject = $subject;
        $this->code = $code;
        $this->occur = new \DateTimeImmutable();
    }

    public function getVerificationId(): VerificationId
    {
        return $this->verificationId;
    }

    public function getSubject(): Subject
    {
        return $this->subject;
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
