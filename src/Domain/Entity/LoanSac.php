<?php

declare(strict_types=1);

namespace Src\Domain\Entity;

use Brick\Math\RoundingMode;
use Brick\Money\Money;

class LoanSac extends AbstractLoan
{
    public function generateInstallments(): array
    {
        /** @var array<Installment> */
        $installments = [];
        $balance = Money::of($this->amount, 'BRL');
        $rate = $this->rate / 100;
        $installmentNumber = 1;

        $amortization = $balance->dividedBy($this->period, RoundingMode::HALF_UP);
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
