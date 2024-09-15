<?php

namespace Tests\Integration;

use Ramsey\Uuid\Uuid;
use Src\Application\UseCase\SimulateLoan;

test('Deve simular um financiamento utilizando a tabela price', function () {
    $simulateLoan = new SimulateLoan();
    $input = (object) [
        'code' => Uuid::uuid4()->toString(),
        'purchasePrice' => 250000,
        'downPayment' => 50000,
        'salary' => 70000,
        'period' => 12,
        'type' => 'price'
    ];

    $output = $simulateLoan->execute($input);

    expect($output->installments)->toHaveCount(12);
    $firstInstallment = $output->installments[0];
    expect($firstInstallment->balance)->toBe(184230.24);
    $lastInstallment = $output->installments[count($output->installments) - 1];
    expect($lastInstallment->balance)->toBe(0.0);
});

test('Deve simular um financiamento utilizando a tabela sac', function () {
    $simulateLoan = new SimulateLoan();
    $input = (object) [
        'code' => Uuid::uuid4()->toString(),
        'purchasePrice' => 250000,
        'downPayment' => 50000,
        'salary' => 70000,
        'period' => 12,
        'type' => 'sac'
    ];

    $output = $simulateLoan->execute($input);

    expect($output->installments)->toHaveCount(12);
    $firstInstallment = $output->installments[0];
    expect($firstInstallment->balance)->toBe(183333.33);
    $lastInstallment = $output->installments[count($output->installments) - 1];
    expect($lastInstallment->balance)->toBe(0.0);
});
