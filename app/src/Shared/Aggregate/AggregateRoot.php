<?php

declare(strict_types=1);

namespace App\Shared\Aggregate;

use App\Shared\Event\DomainEventInterface;
use App\Shared\Message\DomainMessage;

abstract class AggregateRoot extends DomainMessage
{
    protected array $domainEvents;

    public function recordDomainEvent(DomainEventInterface $event): self
    {
        $this->domainEvents[] = $event;

        return $this;
    }

    public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }
}
