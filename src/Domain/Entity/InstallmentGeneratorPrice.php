<?php

declare(strict_types=1);

namespace Src\Domain\Entity;

use Brick\Math\RoundingMode;
use Brick\Money\Money;

class InstallmentGeneratorPrice implements InstallmentGenerator
{
    public function generate(string $loanCode, float $loanAmount, int $loanPeriod, float $loanRate): array
    {
        // step 1
        /** @var array<Installment> */
        $installments = [];
        $balance = Money::of($loanAmount, 'BRL');
        $rate = $loanRate / 100;
        $installmentNumber = 1;

        // step 2
        $formula = pow(1 + $rate, $loanPeriod);
        $balanceFormula = $formula * $rate / ($formula - 1);
        $amount = $balance->multipliedBy($balanceFormula, RoundingMode::HALF_UP);

        while ($balance->isGreaterThan(Money::of(0, 'BRL'))) {
            // step 3
            $interest = $balance->multipliedBy($rate, RoundingMode::HALF_UP);
            $amortization = $amount->minus($interest);
            $balance = $balance->minus($amortization);

            // step 4
            if ($balance->isLessThanOrEqualTo(Money::of(0.05, 'BRL'))) {
                $balance = Money::of(0, 'BRL');
            }
            $installments[] = new Installment(
                $loanCode,
                $installmentNumber,
                $amount->getAmount()->toFloat(),
                $interest->getAmount()->toFloat(),
                $amortization->getAmount()->toFloat(),
                $balance->getAmount()->toFloat()
            );
            $installmentNumber++;
        }
        return $installments;
    }
}
