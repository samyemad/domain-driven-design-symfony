<?php

declare(strict_types=1);

namespace App\Notification\Domain\Repository;

use App\Notification\Domain\Entity\Notification;

interface NotificationRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function findAll();

    public function save(Notification $notification): void;
}
