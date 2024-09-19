<?php

declare(strict_types=1);

namespace Src\Application\UseCase;

interface UseCase
{
    public function execute(object $input): object;
}
