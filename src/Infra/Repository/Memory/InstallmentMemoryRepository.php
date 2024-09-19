<?php

declare(strict_types=1);

namespace Src\Infra\Repository\Memory;

use Src\Application\Repository\InstallmentRepository;
use Src\Domain\Entity\Installment;

class InstallmentMemoryRepository implements InstallmentRepository
{

    public static ?InstallmentMemoryRepository $instance = null;
    /**
     * Summary of loans
     *
     * @var array<Installment>
     */
    private array $installments;

    private function __construct()
    {
        $this->installments = [];
    }

    public static function getInstance(): InstallmentMemoryRepository
    {
        if (! isset(self::$instance)) {
            self::$instance = new InstallmentMemoryRepository();
        }
        return self::$instance;
    }

    public function save(Installment $installment): void
    {
        $this->installments[] = $installment;
    }

    public function getByCode(string $code): array
    {
        return array_filter($this->installments, static fn (Installment $i) => $i->loanCode === $code);
    }
}
