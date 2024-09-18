<?php

declare(strict_types=1);

namespace Src\Application\Repository;

use Src\Domain\Entity\Loan;

interface LoanRepository
{
    public function save(Loan $loan): void;
    public function getByCode(string $code): Loan;
}
