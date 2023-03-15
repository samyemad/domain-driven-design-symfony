<?php

declare(strict_types=1);

namespace App\Notification\Application\Model;

final class DispatchNotificationCommand
{
    private string $notificationId;

    private string $recipient;

    private string $channel;

    private string $body;

    public function __construct(string $notificationId, string $recipient, string $channel, string $body)
    {
        $this->notificationId = $notificationId;
        $this->recipient = $recipient;
        $this->channel = $channel;
        $this->body = $body;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getBody(): string
    {
        return $this->body;
    }
    public function getNotificationId(): string
    {
        return $this->notificationId;
    }
}
