<?php

declare(strict_types=1);

namespace Src\Domain\Entity;

use Brick\Math\RoundingMode;
use Brick\Money\Money;

class LoanPrice extends AbstractLoan
{
    public function generateInstallments(): array
    {
        /** @var array<Installment> */
        $installments = [];
        $balance = Money::of($this->amount, 'BRL');
        $rate = $this->rate / 100;
        $installmentNumber = 1;

        $formula = pow(1 + $rate, $this->period);
        $balanceFormula = $formula * $rate / ($formula - 1);
        $amount = $balance->multipliedBy($balanceFormula, RoundingMode::HALF_UP);
        while ($balance->isGreaterThan(Money::of(0, 'BRL'))) {
            $interest = $balance->multipliedBy($rate, RoundingMode::HALF_UP);
            $amortization = $amount->minus($interest);
            $balance = $balance->minus($amortization);
            if ($balance->isLessThanOrEqualTo(Money::of(0.05, 'BRL'))) {
                $balance = Money::of(0, 'BRL');
            }
            $installments[] = new Installment(
                $this->code,
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
