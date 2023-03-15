<?php

declare(strict_types=1);

namespace App\Notification\Application\Model;

final class CreateNotificationCommand
{

    private string $recipient;

    private string $channel;

    private string $code;

    public function __construct(string $recipient, string $channel, string $code)
    {
        $this->recipient = $recipient;
        $this->channel = $channel;
        $this->code = $code;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
