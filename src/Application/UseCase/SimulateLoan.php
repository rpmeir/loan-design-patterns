<?php

declare(strict_types=1);

namespace Src\Application\UseCase;

use Src\Domain\Factory\InstallmentGeneratorFactory;

class SimulateLoan
{
    /**
     * Summary of execute
     *
     * @param object{code:string,purchasePrice:float,downPayment:float,salary:float,period:int,type:string} $input
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

        if ($input->salary * 0.25 < $loanAmount / $loanPeriod) {
            throw new \InvalidArgumentException('Insufficient salary');
        }
        $generateInstallments = InstallmentGeneratorFactory::create($loanType);
        $installments = $generateInstallments->generate($input->code, $loanAmount, $loanPeriod, $loanRate);
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
