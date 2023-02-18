<?php

declare(strict_types=1);

namespace App\Verification\Domain\Entity;

use App\Verification\Domain\Repository\VerificationRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

final class Subject
{
    private const PHONE_REGEX = "^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$^";
    private const EMAIL_CONFIRMATION = 'email_confirmation';
    private const MOBILE_CONFIRMATION = 'mobile_confirmation';

    private array $allowedTypes = [self::EMAIL_CONFIRMATION, self::MOBILE_CONFIRMATION];

    private string $identity;

    private string $identityType;

    public function __construct(string $identity, string $identityType)
    {
        if ($this->ensureIsValidType($identityType, $identity)) {
            $this->identityType = $identityType;
            $this->identity = $identity;
        }
    }

    public function getIdentity(): string
    {
        return $this->identity;
    }

    public function getIdentityType(): string
    {
        return $this->identityType;
    }

    /**
     * check the same subject found before in our DB or not.
     */
    public function isEqual(VerificationRepositoryInterface $verificationRepository, Subject $currentSubject): bool
    {
        $result = $verificationRepository->findOneBy(['subject.identity' => $currentSubject->getIdentity(), 'subject.identityType' => $currentSubject->getIdentityType()]);
        if (!empty($result) && !$result->isConfirmed()) {
            return false;
        }

        return true;
    }

    /**
     * handle ensuring the valid tyoe related to identity.
     */
    private function ensureIsValidType(string $identityType, string $identity): bool
    {
        if (!in_array($identityType, $this->allowedTypes)) {
            throw new \Exception(sprintf('The type %s is not valid', $identityType), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return $this->ensureIsValidIdentity($identityType, $identity);
    }

    /**
     * handle ensuring the valid identity based on tyoe and add domain messages if errors found.
     */
    private function ensureIsValidIdentity(string $identityType, string $identity): bool
    {
        if (self::EMAIL_CONFIRMATION == $identityType && false === filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception(sprintf('The email <%s> is not valid', $identity), Response::HTTP_UNPROCESSABLE_ENTITY);
        } elseif (self::MOBILE_CONFIRMATION == $identityType && false == preg_match(self::PHONE_REGEX, $identity)) {
            throw new \Exception(sprintf('The mobile <%s> is not valid', $identity), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return true;
    }
}
