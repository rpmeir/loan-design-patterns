<?php

namespace Src\Domain\Entity;

use Brick\Math\RoundingMode;
use Brick\Money\Money;

class InstallmentGeneratorSac implements InstallmentGenerator
{
    public function generate(string $loanCode, float $loanAmount, int $loanPeriod, float $loanRate): array
    {
        /** @var array<Installment> */
        $installments = [];
        $balance = Money::of($loanAmount, 'BRL');
        $rate = $loanRate / 100;
        $installmentNumber = 1;

        $amortization = $balance->dividedBy($loanPeriod, RoundingMode::HALF_UP);
        while ($balance->isGreaterThan(Money::of(0.0, 'BRL'))) {
            $initialBalance = Money::of($balance->getAmount(), 'BRL');
            $interest = Money::of($initialBalance->getAmount()->toFloat() * $rate, 'BRL', null, RoundingMode::HALF_UP);
            $updatedBalance = Money::of($initialBalance->getAmount()->toFloat() + $interest->getAmount()->toFloat(), 'BRL');
            $amount = Money::of($interest->getAmount()->toFloat() + $amortization->getAmount()->toFloat(), 'BRL');
            $balance = Money::of($updatedBalance->getAmount()->toFloat() - $amount->getAmount()->toFloat(), 'BRL', null, RoundingMode::HALF_UP);
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
