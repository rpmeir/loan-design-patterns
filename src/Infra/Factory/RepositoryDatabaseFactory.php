<?php

declare(strict_types=1);

namespace Src\Infra\Factory;

use Src\Application\Factory\RepositoryAbstractFactory;
use Src\Application\Repository\InstallmentRepository;
use Src\Application\Repository\LoanRepository;
use Src\Infra\Database\Connection;
use Src\Infra\Repository\Database\InstallmentDatabaseRepository;
use Src\Infra\Repository\Database\LoanDatabaseRepository;

class RepositoryDatabaseFactory implements RepositoryAbstractFactory
{
    public function __construct(public readonly Connection $connection)
    {
    }

    public function createLoanRepository(): LoanRepository
    {
        return new LoanDatabaseRepository($this->connection);
    }

    public function createInstallmentRepository(): InstallmentRepository
    {
        return new InstallmentDatabaseRepository($this->connection);
    }
}
