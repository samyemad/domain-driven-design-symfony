<?php

declare(strict_types=1);

namespace App\Notification\Application\EventSubscriber;

use App\Notification\Application\Model\DispatchNotificationCommand;
use App\Notification\Domain\Event\NotificationCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OnNotificationCreatedEventSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(
        MessageBusInterface $messageBus,
    ) {
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NotificationCreatedEvent::class => 'dispatchNotification',
        ];
    }

    /**
     * notify the NotificationCreatedEvent and create DispatchNotificationCommand.
     */
    public function dispatchNotification(NotificationCreatedEvent $event): void
    {
        $dispatchNotificationCommand = new DispatchNotificationCommand($event->getNotificationId()->getValue());
        $dispatchNotificationCommand->setChannel($event->getChannel());
        $dispatchNotificationCommand->setRecipient($event->getRecipient());
        $dispatchNotificationCommand->setBody($event->getBody());
        $this->messageBus->dispatch($dispatchNotificationCommand);
    }
}
