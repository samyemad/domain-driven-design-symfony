<?php

declare(strict_types=1);

namespace App\Verification\Domain\Service\Interfaces;

use App\Verification\Domain\Entity\Subject;
use App\Verification\Domain\Entity\Verification;
use App\Verification\Domain\Entity\VerificationId;

interface CreateVerificationInterface
{
    public function process(VerificationId $verificationId, Verification $verification, Subject $subject): array;
}
