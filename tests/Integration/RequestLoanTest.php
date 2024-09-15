<?php

namespace Tests\Integration;

use Ramsey\Uuid\Uuid;
use Src\Application\UseCase\GetLoan;
use Src\Application\UseCase\RequestLoan;

test('Deve aplicar um financiamento utilizanodo a tabela price', function () {
    $code = Uuid::uuid4()->toString();
    $requestLoan = new RequestLoan();
    $inputRequestLoan = (object) [
        'code' => $code,
        'purchasePrice' => 250000,
        'downPayment' => 50000,
        'salary' => 70000,
        'period' => 12,
        'type' => 'price'
    ];

    $requestLoan->execute($inputRequestLoan);
    $getLoan = new GetLoan();
    $inputGetLoan = (object) ['code' => $code];
    $output = $getLoan->execute($inputGetLoan);

    expect($output->installments)->toHaveCount(12);
    $firstInstallment = $output->installments[0];
    expect($firstInstallment->balance)->toBe(184230.24);
    $lastInstallment = $output->installments[count($output->installments) - 1];
    expect($lastInstallment->balance)->toBe(0.0);
});

test('Deve aplicar um financiamento utilizanodo a tabela sac', function () {
    $code = Uuid::uuid4()->toString();
    $requestLoan = new RequestLoan();
    $inputRequestLoan = (object) [
        'code' => $code,
        'purchasePrice' => 250000,
        'downPayment' => 50000,
        'salary' => 70000,
        'period' => 12,
        'type' => 'sac'
    ];

    $requestLoan->execute($inputRequestLoan);
    $getLoan = new GetLoan();
    $inputGetLoan = (object) ['code' => $code];
    $output = $getLoan->execute($inputGetLoan);

    expect($output->installments)->toHaveCount(12);
    $firstInstallment = $output->installments[0];
    expect($firstInstallment->balance)->toBe(183333.33);
    $lastInstallment = $output->installments[count($output->installments) - 1];
    expect($lastInstallment->balance)->toBe(0.0);
});
