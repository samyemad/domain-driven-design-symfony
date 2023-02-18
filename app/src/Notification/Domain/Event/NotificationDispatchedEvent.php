<?php

declare(strict_types=1);

namespace App\Notification\Domain\Event;

use App\Notification\Domain\Entity\NotificationId;
use App\Shared\Event\DomainEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class NotificationDispatchedEvent extends Event implements DomainEventInterface
{
    protected \DateTimeImmutable $occur;

    protected NotificationId $notificationId;

    public function __construct(NotificationId $notificationId)
    {
        $this->notificationId = $notificationId;
        $this->occur = new \DateTimeImmutable();
    }

    public function getNotificationId(): NotificationId
    {
        return $this->notificationId;
    }

    public function getOccur(): \DateTimeImmutable
    {
        return $this->occur;
    }
}
