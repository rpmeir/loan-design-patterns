<?php

declare(strict_types=1);

namespace Src\Domain\Entity;

interface InstallmentGenerator
{
    /**
     * Summary of generate
     *
     * @return array<Installment>
     */
    public function generate(string $loanCode, float $loanAmount, int $loanPeriod, float $loanRate): array;
}
