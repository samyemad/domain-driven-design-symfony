<?php

declare(strict_types=1);

namespace App\Verification\Infrastructure\Repository;

use App\Verification\Domain\Entity\Verification;
use App\Verification\Domain\Repository\VerificationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class VerificationRepository extends ServiceEntityRepository implements VerificationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Verification::class);
    }

    public function save(Verification $verification): void
    {
        $this->_em->persist($verification);
        $this->_em->flush();
    }
}
