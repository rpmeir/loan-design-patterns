<?php

declare(strict_types=1);

namespace Src\Application\Factory;

use Src\Application\Repository\InstallmentRepository;
use Src\Application\Repository\LoanRepository;

interface RepositoryAbstractFactory
{
    public function createLoanRepository(): LoanRepository;
    public function createInstallmentRepository(): InstallmentRepository;
}
