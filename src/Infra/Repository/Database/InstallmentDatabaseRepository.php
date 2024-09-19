<?php

declare(strict_types=1);

namespace Src\Infra\Repository\Database;

use Src\Application\Repository\InstallmentRepository;
use Src\Domain\Entity\Installment;
use Src\Infra\Database\Connection;

class InstallmentDatabaseRepository implements InstallmentRepository
{
    public function __construct(public readonly Connection $connection)
    {
    }

    public function save(Installment $installment): void
    {
        $this->connection->query(
            'INSERT INTO loan.installments (loan_code, number, amount, interest, amortization, balance) VALUES (?, ?, ?, ?, ?, ?)',
            [$installment->loanCode, $installment->number, $installment->amount, $installment->interest, $installment->amortization, $installment->balance]
        );
    }

    /**
     * Summary of getByCode
     *
     * @return array<Installment>
     */
    public function getByCode(string $code): array
    {
        $installmentsData = $this->connection->query('SELECT * FROM loan.installments WHERE loan_code = ?', [$code]);
        $installments = [];
        foreach ($installmentsData as $installment) {
            $installments[] = new Installment(
                (string) $installment['loan_code'],
                (int) $installment['number'],
                (float) $installment['amount'],
                (float) $installment['interest'],
                (float) $installment['amortization'],
                (float) $installment['balance']
            );
        }
        return $installments;
    }
}
