<?php

declare(strict_types=1);

namespace Src\Application\Repository;

use Src\Domain\Entity\Installment;

interface InstallmentRepository
{
    public function save(Installment $installment): void;
    /**
     * Summary of getByCode
     *
     * @return array<Installment>
     */
    public function getByCode(string $code): array;
}
