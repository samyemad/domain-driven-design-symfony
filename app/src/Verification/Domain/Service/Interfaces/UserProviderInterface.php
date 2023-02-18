<?php

declare(strict_types=1);

namespace App\Verification\Domain\Service\Interfaces;

use App\Verification\Domain\Entity\UserInfo;

interface UserProviderInterface
{
    public function process(): UserInfo;
}
