<?php

declare(strict_types=1);

namespace App\Notification\Domain\Entity;

use App\Notification\Domain\Event\NotificationCreatedEvent;
use App\Notification\Domain\Event\NotificationDispatchedEvent;
use App\Shared\Aggregate\AggregateRoot;

class Notification extends AggregateRoot
{
    private string $id;

    private bool $dispatched = false;

    private string $recipient;

    private string $channel;

    private string $body;

    public function __construct(NotificationId $id,string $recipient,string $channel,string $body)
    {
        $this->id = $id->getValue();
        $this->recipient = $recipient;
        $this->channel = $channel;
        $this->body = $body;
        $this->recordDomainEvent(new NotificationCreatedEvent($this->getId(), $channel, $body, $recipient));
    }

    public function getId(): ?NotificationId
    {
        return new NotificationId($this->id);
    }

    public function getBody(): string
    {
        return $this->body;
    }

    private function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getDispatched(): bool
    {
        return $this->dispatched;
    }

    private function setDispatched(bool $dispatched): void
    {
        $this->dispatched = $dispatched;
    }

    public function getRecipient(): string
    {
        return $this->recipient;
    }

    private function setRecipient(string $recipient): void
    {
        $this->recipient = $recipient;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    private function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }
    public function updateDispatch(): self
    {
        $this->setDispatched(true);
        $this->recordDomainEvent(new NotificationDispatchedEvent($this->getId()));
        return $this;
    }
}
