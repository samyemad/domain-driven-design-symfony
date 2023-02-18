<?php

declare(strict_types=1);

namespace App\Notification\Domain\Event;

use App\Notification\Domain\Entity\NotificationId;
use App\Shared\Event\DomainEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class NotificationCreatedEvent extends Event implements DomainEventInterface
{
    protected NotificationId $notificationId;

    private \DateTimeImmutable $occur;

    private string $channel;

    private string $body;

    private string $recipient;

    public function __construct(NotificationId $notificationId, string $channel, string $body, string $recipient)
    {
        $this->channel = $channel;
        $this->body = $body;
        $this->recipient = $recipient;
        $this->occur = new \DateTimeImmutable();
        $this->notificationId = $notificationId;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getOccur(): \DateTimeImmutable
    {
        return $this->occur;
    }

    public function getNotificationId(): NotificationId
    {
        return $this->notificationId;
    }
}
