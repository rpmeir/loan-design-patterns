<?php

namespace Src\Application\UseCase;

use Brick\Math\RoundingMode;
use Brick\Money\Money;

class SimulateLoan
{

    /**
     * Summary of execute
     * @param object{code:string,purchasePrice:float,downPayment:float,salary:float,period:float,type:string} $input
     * @return object{code:string, installments:object{installmentNumber:int,amount:float,interest:float,amortization:float,balance:float}[]}
     */
    public function execute(object $input): object
    {
        $output = (object) [
            'code' => $input->code,
            'installments' => [],
        ];

        $loanAmount = $input->purchasePrice - $input->downPayment;
        $loanPeriod = $input->period;
        $loanRate = 1;
        $loanType = $input->type;

        if($input->salary * 0.25 < ($loanAmount/$loanPeriod)) {
            throw new \InvalidArgumentException('Insufficient salary');
        }

        $balance = Money::of($loanAmount, 'BRL');
        $rate = $loanRate / 100;
        $installmentNumber = 1;
        if($loanType === 'price') {
            $formula =  pow(1 + $rate, $loanPeriod);
            $formulaRate = $formula * $rate;
            $formulaMinusOne = $formula - 1;
            $balanceFormula = $formulaRate / $formulaMinusOne;
            $amount = $balance->multipliedBy($balanceFormula, RoundingMode::HALF_UP);
            while($balance->isGreaterThan(Money::of(0, 'BRL'))) {
                $interest = $balance->multipliedBy($rate, RoundingMode::HALF_UP);
                $amortization = $amount->minus($interest);
                $balance = $balance->minus($amortization);
                if($balance->isLessThanOrEqualTo(Money::of(0.05, 'BRL'))) {
                    $balance = Money::of(0, 'BRL');
                }
                $output->installments[] = (object) [
                    'installmentNumber' => $installmentNumber,
                    'amount' => $amount->getAmount()->toFloat(),
                    'interest' => $interest->getAmount()->toFloat(),
                    'amortization' => $amortization->getAmount()->toFloat(),
                    'balance' => $balance->getAmount()->toFloat(),
                ];
                $installmentNumber++;
            }
        }
        if($loanType === 'sac') {
            $amortization = $balance->dividedBy($loanPeriod, RoundingMode::HALF_UP);
            while($balance->isGreaterThan(Money::of(0.0, 'BRL'))) {
                $initialBalance = Money::of($balance->getAmount(), 'BRL');
                $interest = Money::of($initialBalance->getAmount()->toFloat() * $rate, 'BRL', null, RoundingMode::HALF_UP);
                $updatedBalance = Money::of($initialBalance->getAmount()->toFloat() + $interest->getAmount()->toFloat(), 'BRL');
                $amount = Money::of($interest->getAmount()->toFloat() + $amortization->getAmount()->toFloat(), 'BRL');
                $balance = Money::of($updatedBalance->getAmount()->toFloat() - $amount->getAmount()->toFloat(), 'BRL', null, RoundingMode::HALF_UP);
                if($balance->isLessThanOrEqualTo(Money::of(0.05, 'BRL'))) {
                    $balance = Money::of(0, 'BRL');
                }
                $output->installments[] = (object) [
                    'installmentNumber' => $installmentNumber,
                    'amount' => $amount->getAmount()->toFloat(),
                    'interest' => $interest->getAmount()->toFloat(),
                    'amortization' => $amortization->getAmount()->toFloat(),
                    'balance' => $balance->getAmount()->toFloat(),
                ];
                $installmentNumber++;
            }
        }
        return $output;
    }
}
