<?php

namespace Tests\Integration;

use Ramsey\Uuid\Uuid;
use Src\Application\Decorator\LogDecorator;
use Src\Application\UseCase\GetLoan;
use Src\Application\UseCase\RequestLoan;
use Src\Infra\Database\PostgresConnection;
use Src\Infra\Factory\RepositoryDatabaseFactory;
use Src\Infra\Factory\RepositoryMemoryFactory;

test('Deve aplicar um financiamento utilizando a tabela price', function () {
    $code = Uuid::uuid4()->toString();
    $connection = new PostgresConnection();
    $repositoryFactory = new RepositoryDatabaseFactory($connection);
    //$repositoryFactory = new RepositoryMemoryFactory();
    $requestLoan = new LogDecorator(new RequestLoan($repositoryFactory));
    $inputRequestLoan = (object) [
        'code' => $code,
        'purchasePrice' => 250000,
        'downPayment' => 50000,
        'salary' => 70000,
        'period' => 12,
        'type' => 'price'
    ];

    $requestLoan->execute($inputRequestLoan);
    $getLoan = new LogDecorator(new GetLoan($repositoryFactory));
    $inputGetLoan = (object) ['code' => $code];
    $output = $getLoan->execute($inputGetLoan);
    $connection->close();

    expect($output->installments)->toHaveCount(12);
    $firstInstallment = $output->installments[0];
    expect($firstInstallment->balance)->toBe(184230.24);
    $lastInstallment = $output->installments[count($output->installments) - 1];
    expect($lastInstallment->balance)->toBe(0.0);
});

test('Deve aplicar um financiamento utilizando a tabela sac', function () {
    $code = Uuid::uuid4()->toString();
    $connection = new PostgresConnection();
    $repositoryFactory = new RepositoryDatabaseFactory($connection);
    //$repositoryFactory = new RepositoryMemoryFactory();
    $requestLoan = new RequestLoan($repositoryFactory);
    $inputRequestLoan = (object) [
        'code' => $code,
        'purchasePrice' => 250000,
        'downPayment' => 50000,
        'salary' => 70000,
        'period' => 12,
        'type' => 'sac'
    ];

    $requestLoan->execute($inputRequestLoan);
    $getLoan = new GetLoan($repositoryFactory);
    $inputGetLoan = (object) ['code' => $code];
    $output = $getLoan->execute($inputGetLoan);
    $connection->close();

    expect($output->installments)->toHaveCount(12);
    $firstInstallment = $output->installments[0];
    expect($firstInstallment->balance)->toBe(183333.33);
    $lastInstallment = $output->installments[count($output->installments) - 1];
    expect($lastInstallment->balance)->toBe(0.0);
});
