<?php

declare(strict_types=1);

namespace Src\Infra\Repository\Memory;

use Src\Application\Repository\LoanRepository;
use Src\Domain\Entity\Loan;

class LoanMemoryRepository implements LoanRepository
{

    public static ?LoanMemoryRepository $instance = null;
    /**
     * Summary of loans
     *
     * @var array<Loan>
     */
    private array $loans;

    private function __construct()
    {
        $this->loans = [];
    }

    public static function getInstance(): LoanMemoryRepository
    {
        if (! isset(self::$instance)) {
            self::$instance = new LoanMemoryRepository();
        }
        return self::$instance;
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
