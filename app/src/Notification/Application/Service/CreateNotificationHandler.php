<?php

declare(strict_types=1);

namespace App\Notification\Application\Service;

use App\Notification\Application\Model\CreateNotificationCommand;
use App\Notification\Domain\Entity\Notification;
use App\Notification\Domain\Entity\NotificationId;
use App\Notification\Domain\Repository\NotificationRepositoryInterface;
use App\Notification\Domain\Service\Interfaces\TemplateRenderProviderInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateNotificationHandler implements MessageHandlerInterface
{
    private NotificationRepositoryInterface $notificationRepository;
    private EventDispatcherInterface $eventDispatcher;
    private TemplateRenderProviderInterface $templateRenderProvider;

    public function __construct(
        NotificationRepositoryInterface $notificationRepository,
        EventDispatcherInterface $eventDispatcher,
        TemplateRenderProviderInterface $templateRenderProvider
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->templateRenderProvider = $templateRenderProvider;
    }

    /**
     * handle the CreateNotificationCommand and render template based on channel and save notification.
     */
    public function __invoke(CreateNotificationCommand $createNotificationCommand): void
    {
        $body = $this->templateRenderProvider->process($createNotificationCommand->getChannel(), $createNotificationCommand->getCode());

        if ('' != $body) {
            $notificationId = new NotificationId(Uuid::uuid4()->toString());
            $notification = new Notification($notificationId);
            $notification->create($createNotificationCommand->getRecipient(), $createNotificationCommand->getChannel(), $body);
            $this->notificationRepository->save($notification);
            foreach ($notification->pullDomainEvents() as $domainEvent) {
                $this->eventDispatcher->dispatch($domainEvent);
            }
        }
    }
}
