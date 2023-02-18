<?php

declare(strict_types=1);

namespace App\Notification\Application\EventSubscriber;

use App\Notification\Application\Model\CreateNotificationCommand;
use App\Notification\Domain\Service\Interfaces\TranslatorProviderInterface;
use App\Verification\Domain\Event\VerificationCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OnVerificationCreatedEventSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $messageBus;
    private TranslatorProviderInterface $translatorProvider;

    public function __construct(
        MessageBusInterface $messageBus,
        TranslatorProviderInterface $translatorProvider
    ) {
        $this->messageBus = $messageBus;
        $this->translatorProvider = $translatorProvider;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            VerificationCreatedEvent::class => 'createNotification',
        ];
    }

    /**
     * notify the VerificationCreatedEvent and translate the object comes from Verification and create NotificationCommand.
     */
    public function createNotification(VerificationCreatedEvent $event): void
    {
        $createNotificationCommand = new CreateNotificationCommand();
        $channel = $this->translatorProvider->translateChannel($event->getSubject()->getIdentityType());
        $createNotificationCommand->setChannel($channel);
        $createNotificationCommand->setRecipient($event->getSubject()->getIdentity());
        $createNotificationCommand->setCode($event->getCode());
        $this->messageBus->dispatch($createNotificationCommand);
    }
}
