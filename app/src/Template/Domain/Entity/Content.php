<?php

declare(strict_types=1);

namespace App\Template\Domain\Entity;

final class Content
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
