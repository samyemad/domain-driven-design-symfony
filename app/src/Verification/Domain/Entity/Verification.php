<?php

declare(strict_types=1);

namespace App\Verification\Domain\Entity;

use App\Shared\Aggregate\AggregateRoot;
use App\Verification\Domain\Event\VerificationConfirmationFailedEvent;
use App\Verification\Domain\Event\VerificationConfirmedEvent;
use App\Verification\Domain\Event\VerificationCreatedEvent;
use App\Verification\Domain\Repository\VerificationRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class Verification extends AggregateRoot
{
    private string $id;

    private \DateTimeImmutable $createdAt;

    private \DateTimeImmutable $updatedAt;

    private bool $confirmed = false;

    private bool $expired = false;

    private Subject $subject;

    private UserInfo $userInfo;

    private string $code;

    private int $suppliedTimes = 0;

    public function __construct(VerificationId $id,Subject $subject,UserInfo $userInfo, string $code)
    {
        $this->id = $id->getValue();
        $this->subject = $subject;
        $this->userInfo = $userInfo;
        $this->code = $code;
        $this->createdAt = new \DateTimeImmutable('now');
    }

    public function getId(): ?VerificationId
    {
        return new VerificationId($this->id);
    }

    public function getSubject(): Subject
    {
        return $this->subject;
    }

    private function setSubject(Subject $subject): void
    {
        $this->subject = $subject;
    }

    public function getUserInfo(): UserInfo
    {
        return $this->userInfo;
    }

    private function setUserInfo(UserInfo $userInfo): void
    {
        $this->userInfo = $userInfo;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    private function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    private function setConfirmed(bool $confirmed): void
    {
        $this->confirmed = $confirmed;
    }

    public function isExpired(): bool
    {
        return $this->expired;
    }

    private function setExpired(bool $expired): void
    {
        $this->expired = $expired;
    }

    public function getSuppliedTimes(): ?int
    {
        return $this->suppliedTimes;
    }

    private function setSuppliedTimes(int $suppliedTimes): void
    {
        $this->suppliedTimes = $suppliedTimes;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    private function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    private function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * create verification based on subject and user info and code and record verification created event.
     */
    public function create(VerificationRepositoryInterface $verificationRepository): self
       {
        if ($this->subject->isEqual($verificationRepository)) {
            $this->recordDomainEvent(new VerificationCreatedEvent($this->getId(), $this->code, $this->subject));
        } else {
            $this->addDomainMessage(['code' => Response::HTTP_CONFLICT, 'message' => ' This subject has been before']);
        }

        return $this;
    }

    /**
     * confirm the verification based on code and user info.
     */
    public function confirm(VerificationRepositoryInterface $verificationRepository, string $code, UserInfo $userInfo,string $validationAllowedTime): self
    {
        if ($this->checkVerification() &&
            $this->checkTimeExpiration($validationAllowedTime) &&
            $this->checkClientInfo($userInfo) &&
            $this->checkVerificationCode($verificationRepository, $code)) {
            $this->setConfirmed(true);
            $this->setUpdatedAt(new \DateTimeImmutable('now'));
            $this->addDomainMessage(['code' => Response::HTTP_NO_CONTENT, 'message' => 'Verification confirmed']);
            $this->recordDomainEvent(new VerificationConfirmedEvent($this->getId(), $code));
        } else {
            $this->recordDomainEvent(new VerificationConfirmationFailedEvent($this->getId(), $code));
        }

        return $this;
    }

    /**
     * check verification code is valid or not.
     */
    private function checkVerificationCode(VerificationRepositoryInterface $verificationRepository, string $code): bool
    {
        $result = $verificationRepository->findOneBy(['code' => $code]);
        if (null == $result || ($result->getCode() != $this->getCode())) {
            $this->addDomainMessage(['code' => Response::HTTP_UNPROCESSABLE_ENTITY, 'message' => 'Validation failed: invalid code supplied']);
            $this->checkAndEditSuppliedTimes();

            return false;
        }

        return true;
    }

    /**
     * check the previous supplied times and edit it.
     */
    private function checkAndEditSuppliedTimes(): void
    {
        $totalSuppliedTimes = $this->getSuppliedTimes() + 1;
        $this->setSuppliedTimes($totalSuppliedTimes);
        if ($totalSuppliedTimes >= 5) {
            $this->setExpired(true);
            $this->setUpdatedAt(new \DateTimeImmutable('now'));
            $this->addDomainMessage(['code' => Response::HTTP_GONE, 'message' => 'Verification expired']);
        }
    }

    /**
     * check the current userinfo with userinfo in verification.
     */
    private function checkClientInfo(UserInfo $userInfo): bool
    {
        if ($userInfo != $this->getUserInfo()) {
            $this->addDomainMessage(['code' => Response::HTTP_FORBIDDEN, 'message' => 'No permission to confirm verification']);

            return false;
        }

        return true;
    }

    /**
     * check the time expiration in verification according to current time and created verification time.
     */
    private function checkTimeExpiration($validationAllowedTime): bool
    {
        $createdAtTime = $this->createdAt->getTimestamp();
        $currentTime = time();
        if (($createdAtTime + $validationAllowedTime) <= $currentTime) {
            $this->setExpired(true);
            $this->setUpdatedAt(new \DateTimeImmutable('now'));
            $this->addDomainMessage(['code' => Response::HTTP_GONE, 'message' => 'Verification expired']);
            return false;
        }
        return true;
    }

    /**
     * check verification status (confirmed - expired) before we make confirmation to it.
     */
    private function checkVerification(): bool
    {
        if ($this->isConfirmed()) {
            $this->addDomainMessage(['code' => Response::HTTP_NO_CONTENT, 'message' => 'Verification confirmed']);

            return false;
        } elseif ($this->isExpired()) {
            $this->addDomainMessage(['code' => Response::HTTP_GONE, 'message' => 'Verification expired']);

            return false;
        }

        return true;
    }
}
