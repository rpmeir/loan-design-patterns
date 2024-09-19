<?php

declare(strict_types=1);

namespace Src\Domain\Entity;

abstract class AbstractLoan
{
    private string $type;

    public function __construct(
        public readonly string $code,
        public readonly float $amount,
        public readonly int $period,
        public readonly float $rate,
        public readonly string $loanType,
        public readonly float $salary
    ) {
        if ($loanType !== 'price' && $loanType !== 'sac') {
            throw new \InvalidArgumentException('Invalid loan type');
        }
        if ($salary * 0.25 < $amount / $period) {
            throw new \InvalidArgumentException('Insufficient salary');
        }
        $this->type = $loanType;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Summary of generateInstallments
     *
     * @return array<Installment>
     */
    abstract public function generateInstallments(): array;
}
