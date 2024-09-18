<?php

namespace Src\Infra\Repository\Memory;

use Src\Application\Repository\LoanRepository;
use Src\Domain\Entity\Loan;

class LoanMemoryRepository implements LoanRepository
{
    /**
     * Summary of loans
     * @var array<Loan>
     */
    private array $loans;

    public function __construct()
    {
        $this->loans = [];
    }
    public function save(Loan $loan): void
    {
        $this->loans[$loan->code] = $loan;
    }

    public function getByCode(string $code): Loan
    {
        return $this->loans[$code];
    }
}
