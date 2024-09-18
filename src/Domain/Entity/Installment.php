<?php

declare(strict_types=1);

namespace Src\Domain\Entity;

class Installment
{
    public function __construct(
        public readonly string $loanCode,
        public readonly int $number,
        public readonly float $amount,
        public readonly float $interest,
        public readonly float $amortization,
        public readonly float $balance
    ) {
    }
}
