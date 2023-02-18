<?php

declare(strict_types=1);

namespace App\Verification\Application\Controller\Api;

use App\Verification\Application\Model\ConfirmVerificationCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/verifications/{id}/confirm", name="api_verification_confirm", methods={"PUT"})
 *
 * @ParamConverter(name="confirmVerificationCommand", converter="ConfirmVerification")
 */
final class ConfirmVerificationController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(string $id, ConfirmVerificationCommand $confirmVerificationCommand): JsonResponse
    {
        $result = $this->handle($confirmVerificationCommand);

        return JsonResponse::fromJsonString($result['message'], $result['code']);
    }
}
