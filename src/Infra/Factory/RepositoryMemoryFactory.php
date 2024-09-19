<?php

declare(strict_types=1);

namespace Src\Infra\Factory;

use Src\Application\Factory\RepositoryAbstractFactory;
use Src\Application\Repository\InstallmentRepository;
use Src\Application\Repository\LoanRepository;
use Src\Infra\Repository\Memory\InstallmentMemoryRepository;
use Src\Infra\Repository\Memory\LoanMemoryRepository;

class RepositoryMemoryFactory implements RepositoryAbstractFactory
{
    public function createLoanRepository(): LoanRepository
    {
        return LoanMemoryRepository::getInstance();
    }

    public function createInstallmentRepository(): InstallmentRepository
    {
        return InstallmentMemoryRepository::getInstance();
    }
}
