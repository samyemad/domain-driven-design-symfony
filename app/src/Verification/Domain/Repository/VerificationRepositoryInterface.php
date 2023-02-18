<?php

declare(strict_types=1);

namespace App\Verification\Domain\Repository;

use App\Verification\Domain\Entity\Verification;

interface VerificationRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function findAll();

    public function findOneBy(array $criteria, array $orderBy = null);

    public function save(Verification $verification): void;
}
