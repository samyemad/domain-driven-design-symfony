<?php

declare(strict_types=1);

namespace App\Template\Domain\Repository;

use App\Template\Domain\Entity\Template;

interface TemplateRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function findAll();

    public function save(Template $template): void;
}
