<?php

declare(strict_types=1);

namespace App\Notification\Application\Service;

use App\Notification\Application\Model\DispatchNotificationCommand;
use App\Notification\Domain\Repository\NotificationRepositoryInterface;
use App\Notification\Domain\Service\Interfaces\EmailSenderProviderInterface;
use App\Notification\Domain\Service\Interfaces\SmsSenderProviderInterface;
use App\Shared\Enums\EnumsType;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class DispatchNotificationHandler implements MessageHandlerInterface
{
    private NotificationRepositoryInterface $notificationRepository;
    private EventDispatcherInterface $eventDispatcher;
    private EmailSenderProviderInterface $emailSenderProvider;
    private SmsSenderProviderInterface $smsSenderProvider;

    public function __construct(
        NotificationRepositoryInterface $notificationRepository,
        EventDispatcherInterface $eventDispatcher,
        EmailSenderProviderInterface $emailSenderProvider,
        SmsSenderProviderInterface $smsSenderProvider
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->emailSenderProvider = $emailSenderProvider;
        $this->smsSenderProvider = $smsSenderProvider;
    }

    /**
     * handle the DispatchNotificationCommand and send SMS or Email Based on channel.
     */
    public function __invoke(DispatchNotificationCommand $dispatchNotificationCommand): void
    {
        $notification = $this->notificationRepository->find($dispatchNotificationCommand->getNotificationId());
        if (null == $notification) {
            throw new NotFoundHttpException('Notification not found');
        }
        if (EnumsType::EMAIL_VERIFICATION == $dispatchNotificationCommand->getChannel()) {
            $this->emailSenderProvider->process($dispatchNotificationCommand->getBody(), $dispatchNotificationCommand->getRecipient());
        } elseif (EnumsType::MOBILE_VERIFICATION == $dispatchNotificationCommand->getChannel()) {
            $this->smsSenderProvider->process($dispatchNotificationCommand->getBody());
        }
        $updatedNotification = $notification->updateDispatch();
        $this->notificationRepository->save($updatedNotification);
        foreach ($notification->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }
    }
}
