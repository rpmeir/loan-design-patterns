<?php

namespace Src\Domain\Factory;

use Src\Domain\Entity\InstallmentGenerator;
use Src\Domain\Entity\InstallmentGeneratorPrice;
use Src\Domain\Entity\InstallmentGeneratorSac;

class InstallmentGeneratorFactory
{
    public static function create(string $type): InstallmentGenerator
    {
        switch ($type) {
            case 'price':
                return new InstallmentGeneratorPrice();
            case 'sac':
                return new InstallmentGeneratorSac();
            default:
                throw new \InvalidArgumentException('Invalid installment generator type');
        }
    }
}
