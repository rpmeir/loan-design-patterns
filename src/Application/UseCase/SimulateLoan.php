<?php

declare(strict_types=1);

namespace Src\Application\UseCase;

use Src\Domain\Entity\LoanPrice;
use Src\Domain\Entity\LoanSac;

class SimulateLoan implements UseCase
{
    /**
     * Summary of execute
     *
     * @param object{code:string,purchasePrice:float,downPayment:float,salary:float,period:int,type:string} $input
     *
     * @return object{code:string, installments:object<object{installmentNumber: int, amount: float, interest: float, amortization: float, balance: float}>}
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
        /** @var array<\Src\Domain\Entity\Installment> $installments */
        $installments = [];
        if ($loanType === 'price') {
            $loan = new LoanPrice($input->code, $loanAmount, $loanPeriod, $loanRate, $input->type, $input->salary);
            $installments = $loan->generateInstallments();
        }
        if ($loanType === 'sac') {
            $loan = new LoanSac($input->code, $loanAmount, $loanPeriod, $loanRate, $input->type, $input->salary);
            $installments = $loan->generateInstallments();
        }
        foreach ($installments as $installment) {
            $output->installments[] = (object) [
                'installmentNumber' => $installment->number,
                'amount' => $installment->amount,
                'interest' => $installment->interest,
                'amortization' => $installment->amortization,
                'balance' => $installment->balance,
            ];
        }
        return $output;
    }
}
