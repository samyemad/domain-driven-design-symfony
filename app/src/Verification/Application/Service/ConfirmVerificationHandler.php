<?php

declare(strict_types=1);

namespace App\Verification\Application\Service;

use App\Verification\Application\Model\ConfirmVerificationCommand;
use App\Verification\Domain\Repository\VerificationRepositoryInterface;
use App\Verification\Domain\Service\Interfaces\UserProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ConfirmVerificationHandler implements MessageHandlerInterface
{
    private VerificationRepositoryInterface $verificationRepository;
    private UserProviderInterface $userProvider;
    private SerializerInterface $serializer;
    private string $validationAllowedTime;

    public function __construct(
        VerificationRepositoryInterface $verificationRepository,
        SerializerInterface $serializer,
        UserProviderInterface $userProvider,
        string $validationAllowedTime
    ) {
        $this->verificationRepository = $verificationRepository;
        $this->serializer = $serializer;
        $this->userProvider = $userProvider;
        $this->validationAllowedTime= $validationAllowedTime;
    }

    /**
     * handle confirm verification command and check verification confirmed or expired before  and then confirm it.
     */
    public function __invoke(ConfirmVerificationCommand $confirmVerificationCommand): array
    {
        $result = [];
        $verification = $this->verificationRepository->find($confirmVerificationCommand->getVerificationId());
        if (null == $verification) {
            throw new NotFoundHttpException('Verification not found');
        }
        $currentUserInfo = $this->userProvider->process();
        $verification->confirm($this->verificationRepository, $confirmVerificationCommand->getCode(), $currentUserInfo,$this->validationAllowedTime);
        $this->verificationRepository->save($verification);
        if (!empty($verification->getDomainMessages())) {
            $result['code'] = $verification->getDomainMessages()['code'];
            $result['message'] = $this->serializer->serialize($verification->getDomainMessages(), 'json');
        }

        return $result;
    }
}
