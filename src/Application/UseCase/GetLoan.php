<?php

namespace Src\Application\UseCase;

class GetLoan
{
    public function __construct()
    {
        // not implemented yet
    }

    /**
     * Summary of execute
     * @param object{code:string} $input
     * @return object{code:string, installments:object{installmentNumber:int,amount:float,interest:float,amortization:float,balance:float}[]}
     */
    public function execute(object $input): object
    {
        $connection = new \PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres', 'postgres', '123456');

        $sth = $connection->prepare('SELECT code, amount, period, rate, type FROM loan.loans WHERE code = ?');
        $sth->execute([$input->code]);
        $loanData = $sth->fetchAll(\PDO::FETCH_ASSOC)[0];

        $sth = $connection->prepare('SELECT number, amount, interest, amortization, balance FROM loan.installments WHERE loan_code = ?');
        $sth->execute([$input->code]);
        $installmentsData = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $connection = null;

        $output = (object) [
            'code' => $loanData['code'],
            'installments' => [],
        ];
        foreach($installmentsData as $installment) {
            $output->installments[] = (object) [
                'installmentNumber' => $installment['number'],
                'amount' => (float) $installment['amount'],
                'interest' => (float) $installment['interest'],
                'amortization' => (float) $installment['amortization'],
                'balance' => (float) $installment['balance'],
            ];
        }

        return $output;
    }
}
