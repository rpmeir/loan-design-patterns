<?php

declare(strict_types=1);

namespace Src\Application\UseCase;

use Src\Application\Factory\RepositoryAbstractFactory;
use Src\Application\Repository\InstallmentRepository;
use Src\Application\Repository\LoanRepository;
use Src\Domain\Entity\Loan;
use Src\Domain\Factory\InstallmentGeneratorFactory;

class RequestLoan implements UseCase
{
    private LoanRepository $loanRepository;
    private InstallmentRepository $installmentRepository;

    public function __construct(public readonly RepositoryAbstractFactory $repositoryFactory)
    {
        $this->loanRepository = $this->repositoryFactory->createLoanRepository();
        $this->installmentRepository = $this->repositoryFactory->createInstallmentRepository();
    }

    /**
     * Summary of execute
     *
     * @param object{code:string,purchasePrice:float,downPayment:float,salary:float,period:int,type:string} $input
     */
    public function execute(object $input): object
    {
        $loanAmount = $input->purchasePrice - $input->downPayment;
        $loanPeriod = $input->period;
        $loanRate = 1;
        $loanType = $input->type;

        $loan = new Loan($input->code, $loanAmount, $loanPeriod, $loanRate, $loanType, $input->salary);
        $this->loanRepository->save($loan);

        $generateInstallments = InstallmentGeneratorFactory::create($loanType);
        $installments = $generateInstallments->generate($input->code, $loanAmount, $loanPeriod, $loanRate);
        foreach ($installments as $installment) {
            $this->installmentRepository->save($installment);
        }
        return (object) [];
    }
}
