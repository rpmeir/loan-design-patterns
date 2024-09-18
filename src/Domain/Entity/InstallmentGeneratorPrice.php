<?php

namespace Src\Domain\Entity;

use Brick\Math\RoundingMode;
use Brick\Money\Money;

class InstallmentGeneratorPrice implements InstallmentGenerator
{
    public function generate(string $loanCode, float $loanAmount, int $loanPeriod, float $loanRate): array
    {
        /** @var array<Installment> */
        $installments = [];
        $balance = Money::of($loanAmount, 'BRL');
        $rate = $loanRate / 100;
        $installmentNumber = 1;

        $formula = pow(1 + $rate, $loanPeriod);
        $balanceFormula = ($formula * $rate) / ($formula - 1);
        $amount = $balance->multipliedBy($balanceFormula, RoundingMode::HALF_UP);
        while ($balance->isGreaterThan(Money::of(0, 'BRL'))) {
            $interest = $balance->multipliedBy($rate, RoundingMode::HALF_UP);
            $amortization = $amount->minus($interest);
            $balance = $balance->minus($amortization);
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
