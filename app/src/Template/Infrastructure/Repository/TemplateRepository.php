<?php

declare(strict_types=1);

namespace App\Template\Infrastructure\Repository;

use App\Template\Domain\Entity\Template;
use App\Template\Domain\Repository\TemplateRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class TemplateRepository extends ServiceEntityRepository implements TemplateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Template::class);
    }

    public function save(Template $template): void
    {
        $this->_em->persist($template);
        $this->_em->flush();
    }
}
