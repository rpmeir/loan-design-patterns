<?php

declare(strict_types=1);

namespace Src\Infra\Database;

interface Connection
{
    /**
     * Summary of query
     *
     * @param array<int|string|float> $params
     *
     * @return array<array<string, int|string|float>>
     */
    public function query(string $sql, array $params = []): array;
    public function close(): void;
}
