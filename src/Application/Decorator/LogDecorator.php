<?php

declare(strict_types=1);

namespace Src\Application\Decorator;

use Src\Application\UseCase\UseCase;

class LogDecorator implements UseCase
{
    public function __construct(public readonly UseCase $useCase)
    {
    }

    public function execute(object $input): object
    {
        echo 'LogDecorator: ' . $input->code . PHP_EOL;
        return $this->useCase->execute($input);
    }
}
