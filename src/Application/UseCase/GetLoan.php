<?php

declare(strict_types=1);

namespace Src\Application\UseCase;

use Src\Application\Factory\RepositoryAbstractFactory;
use Src\Application\Repository\InstallmentRepository;
use Src\Application\Repository\LoanRepository;
use Src\Domain\Entity\Installment;

class GetLoan implements UseCase
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
     * @param object{code:string} $input
     *
     * @return object{code:string, installments:object<object<Installment>>}
     */
    public function execute(object $input): object
    {
        $loan = $this->loanRepository->getByCode($input->code);
        $installments = $this->installmentRepository->getByCode($loan->code);

        $output = (object) [
            'code' => $loan->code,
            'installments' => [],
        ];

        foreach ($installments as $installment) {
            $output->installments[] = $installment;
        }

        return $output;
    }
}
