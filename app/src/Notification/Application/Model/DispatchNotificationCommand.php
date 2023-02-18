<?php

declare(strict_types=1);

namespace App\Notification\Application\Model;

final class DispatchNotificationCommand
{
    private string $notificationId;

    private string $recipient;

    private string $channel;

    private string $body;

    public function __construct(string $notificationId)
    {
        $this->notificationId = $notificationId;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): void
    {
        $this->recipient = $recipient;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getNotificationId(): string
    {
        return $this->notificationId;
    }

    public function setNotificationId(string $notificationId): void
    {
        $this->notificationId = $notificationId;
    }
}
