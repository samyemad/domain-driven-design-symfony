<?php

declare(strict_types=1);

namespace App\Verification\Application\Service;

use App\Verification\Application\Model\CreateVerificationCommand;
use App\Verification\Domain\Entity\Subject;
use App\Verification\Domain\Entity\Verification;
use App\Verification\Domain\Entity\VerificationId;
use App\Verification\Domain\Service\Interfaces\CreateVerificationInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateVerificationHandler implements MessageHandlerInterface
{
    private CreateVerificationInterface $createVerification;

    public function __construct(
        CreateVerificationInterface $createVerification
    ) {
        $this->createVerification = $createVerification;
    }

    /**
     * handle confirm verification command and check verification confirmed or expired before  and then confirm it.
     */
    public function __invoke(CreateVerificationCommand $createVerificationCommand): array
    {
        $verificationId = new VerificationId(Uuid::uuid4()->toString());
        $verification = new Verification($verificationId);
        $subject = new Subject($createVerificationCommand->getIdentity(), $createVerificationCommand->getIdentityType());
        return $this->createVerification->process($verificationId, $verification, $subject);
    }
}
