<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Ramsey\Uuid\Uuid;
use Src\Application\UseCase\SimulateLoan;

$simulateLoan = new SimulateLoan();
$input = (object) [
    'code' => Uuid::uuid4()->toString(),
    'purchasePrice' => 250000,
    'downPayment' => 50000,
    'salary' => 70000,
    'period' => 12,
    'type' => 'sac',
];

$output = $simulateLoan->execute($input);

// expect($output->installments)->toHaveCount(12);
[$firstInstallment] = $output->installments;
// expect($firstInstallment->balance)->toBe(184230.24);
[, $lastInstallment] = $output->installments;
// expect($lastInstallment->balance)->toBe(0);

echo '<table><head><tr><th>Installment</th><th>Amount</th><th>Interest</th><th>Amortization</th><th>Balance</th></tr></head><body>';
foreach ($output->installments as $installment) {
    echo '<tr>';
    echo '<td>' . $installment->installmentNumber . '</td>';
    echo '<td>' . $installment->amount . '</td>';
    echo '<td>' . $installment->interest . '</td>';
    echo '<td>' . $installment->amortization . '</td>';
    echo '<td>' . $installment->balance . '</td>';
    echo '</tr>';
}
echo '</body></table>';
