<?php

declare(strict_types=1);

namespace Src\Infra\Repository;

use Src\Application\Repository\LoanRepository;
use Src\Domain\Entity\Loan;
use Src\Infra\Database\Connection;

class LoanDatabaseRepository implements LoanRepository
{
    public function __construct(public readonly Connection $connection)
    {
    }

    public function save(Loan $loan): void
    {
        $this->connection->query(
            'INSERT INTO loan.loans (code, amount, period, rate, type) VALUES (?, ?, ?, ?, ?)',
            [$loan->code, $loan->amount, $loan->period, $loan->rate, $loan->getType()]
        );
    }

    public function getByCode(string $code): Loan
    {
        $loans = $this->connection->query('SELECT code, amount, period, rate, type FROM loan.loans WHERE code = ?', [$code]);
        $loanData = $loans[0];
        return new Loan(
            (string) $loanData['code'],
            (float) $loanData['amount'],
            (int) $loanData['period'],
            (float) $loanData['rate'],
            (string) $loanData['type']
        );
    }
}
