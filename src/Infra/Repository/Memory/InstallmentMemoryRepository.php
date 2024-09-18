<?php

namespace Src\Infra\Repository\Memory;

use Src\Application\Repository\InstallmentRepository;
use Src\Domain\Entity\Installment;

class InstallmentMemoryRepository implements InstallmentRepository
{
    /**
     * Summary of loans
     * @var array<Installment>
     */
    private array $installments;

    public function __construct()
    {
        $this->installments = [];
    }

    public function save(Installment $installment): void
    {
        $this->installments[] = $installment;
    }

    public function getByCode(string $code): array
    {
        return array_filter($this->installments, fn(Installment $i) => $i->loanCode === $code);
    }
}
