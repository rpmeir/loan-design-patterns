<?php

declare(strict_types=1);

namespace Src\Domain\Entity;

class Loan
{
    private string $type;

    public function __construct(
        public readonly string $code,
        public readonly float $amount,
        public readonly int $period,
        public readonly float $rate,
        public readonly string $loanType
    ) {
        if ($loanType !== 'price' && $loanType !== 'sac') {
            throw new \InvalidArgumentException('Invalid loan type');
        }
        $this->type = $loanType;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
