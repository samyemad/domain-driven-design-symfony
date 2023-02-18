<?php

declare(strict_types=1);

namespace App\Verification\Infrastructure\Service;

use App\Verification\Domain\Service\Interfaces\CodeGeneratorProviderInterface;

final class CodeGeneratorProvider implements CodeGeneratorProviderInterface
{
    /**
     * generate random code based on length code provided in environment variables.
     */
    public function process(): string
    {
        return substr(bin2hex(openssl_random_pseudo_bytes(100)), 0, (int) $_ENV['APP_LENGTH_CODE']);
    }
}
