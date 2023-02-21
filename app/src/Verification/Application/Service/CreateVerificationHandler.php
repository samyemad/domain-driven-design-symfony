<?php

declare(strict_types=1);

namespace App\Verification\Application\Service;

use App\Verification\Application\Model\CreateVerificationCommand;
use App\Verification\Domain\Entity\Subject;
use App\Verification\Domain\Entity\Verification;
use App\Verification\Domain\Entity\VerificationId;
use App\Verification\Domain\Service\Interfaces\CodeGeneratorProviderInterface;
use App\Verification\Domain\Service\Interfaces\CreateVerificationInterface;
use App\Verification\Domain\Service\Interfaces\UserProviderInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateVerificationHandler implements MessageHandlerInterface
{
    private CreateVerificationInterface $createVerification;
    private UserProviderInterface $userProvider;
    private CodeGeneratorProviderInterface $codeGeneratorProvider;

    public function __construct(
        CreateVerificationInterface $createVerification,
        UserProviderInterface $userProvider,
        CodeGeneratorProviderInterface $codeGeneratorProvider,
    ) {
        $this->createVerification = $createVerification;
        $this->userProvider = $userProvider;
        $this->codeGeneratorProvider = $codeGeneratorProvider;
    }

    /**
     * handle confirm verification command and check verification confirmed or expired before  and then confirm it.
     */
    public function __invoke(CreateVerificationCommand $createVerificationCommand): array
    {
        $verificationId = new VerificationId(Uuid::uuid4()->toString());

        $subject = new Subject($createVerificationCommand->getIdentity(), $createVerificationCommand->getIdentityType());
        $verification = new Verification($verificationId,$subject,$this->userProvider->process(),$this->codeGeneratorProvider->process());
        return $this->createVerification->process($verificationId, $verification);
    }
}
