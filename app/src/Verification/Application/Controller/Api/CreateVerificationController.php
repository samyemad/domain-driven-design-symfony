<?php

declare(strict_types=1);

namespace App\Verification\Application\Controller\Api;

use App\Verification\Application\Model\CreateVerificationCommand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/verifications", name="api_verification_post", methods={"POST"})
 *
 * @ParamConverter(name="createVerificationCommand", converter="CreateVerification")
 */
final class CreateVerificationController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(CreateVerificationCommand $createVerificationCommand): JsonResponse
    {
        $result = $this->handle($createVerificationCommand);

        return JsonResponse::fromJsonString($result['message'], $result['code']);
    }
}
