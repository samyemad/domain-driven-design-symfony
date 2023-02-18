<?php

declare(strict_types=1);

namespace App\Verification\Domain\Service\Interfaces;

interface CodeGeneratorProviderInterface
{
    public function process(): string;
}
