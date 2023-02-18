<?php

declare(strict_types=1);

namespace App\Verification\Infrastructure\Service;

use App\Verification\Domain\Entity\UserInfo;
use App\Verification\Domain\Service\Interfaces\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    /**
     * get the current user agent and current client ip and then create user info value object and return it.
     */
    public function process(): UserInfo
    {
        $userAgent = '';
        $userIp = '';
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $userIp = $this->getIpAddress();
        }

        return new UserInfo($userIp, $userAgent);
    }

    private function getIpAddress(): string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
