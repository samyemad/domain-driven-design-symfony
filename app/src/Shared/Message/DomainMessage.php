<?php

declare(strict_types=1);

namespace App\Shared\Message;

abstract class DomainMessage
{
    public array $domainMessages = [];

    public function addDomainMessage(array $message): self
    {
        if ($this->checkValidMessage($message)) {
            $this->domainMessages = $message;
        }

        return $this;
    }

    public function getDomainMessages(): array
    {
        return $this->domainMessages;
    }

    private function checkValidMessage(array $message): bool
    {
        if (!array_key_exists('code', $message) || !array_key_exists('message', $message)) {
            throw new \InvalidArgumentException('Not valid Message , Message must contain error and code');
        }

        return true;
    }
}
