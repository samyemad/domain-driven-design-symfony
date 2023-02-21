<?php

declare(strict_types=1);

namespace App\Verification\Infrastructure\Service;

use App\Verification\Domain\Entity\Subject;
use App\Verification\Domain\Entity\Verification;
use App\Verification\Domain\Entity\VerificationId;
use App\Verification\Domain\Repository\VerificationRepositoryInterface;
use App\Verification\Domain\Service\Interfaces\CodeGeneratorProviderInterface;
use App\Verification\Domain\Service\Interfaces\CreateVerificationInterface;
use App\Verification\Domain\Service\Interfaces\UserProviderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class CreateVerification implements CreateVerificationInterface
{
    private EventDispatcherInterface $eventDispatcher;
    private VerificationRepositoryInterface $verificationRepository;
    private SerializerInterface $serializer;


    public function __construct(
        VerificationRepositoryInterface $verificationRepository,
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer,
        string $validationAllowedTime
    ) {
        $this->verificationRepository = $verificationRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
    }

    /**
     * create verification based on verification id and subject and save it to DB and then dispatch our domain events.
     */
    public function process(VerificationId $verificationId, Verification $verification): array
    {
        $verification->create($this->verificationRepository);
        if (empty($verification->getDomainMessages())) {
            $this->verificationRepository->save($verification);
            foreach ($verification->pullDomainEvents() as $domainEvent) {
                $this->eventDispatcher->dispatch($domainEvent);
            }
            $result['code'] = Response::HTTP_OK;
            $result['message'] = $this->serializer->serialize($verificationId, 'json');
        } else {
            $result['code'] = $verification->getDomainMessages()['code'];
            $result['message'] = $this->serializer->serialize($verification->getDomainMessages(), 'json');
        }

        return $result;
    }
}
