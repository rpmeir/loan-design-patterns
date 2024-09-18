<?php

declare(strict_types=1);

namespace Src\Infra\Database;

class PostgresConnection implements Connection
{
    private ?\PDO $connection;

    public function __construct()
    {
        $this->connection = new \PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres', 'postgres', '123456');
    }

    public function query(string $sql, array $params = []): array
    {
        if ($this->connection === null) {
            throw new \Exception('Connection not initialized');
        }
        $sth = $this->connection->prepare($sql);
        $sth->execute($params);
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function close(): void
    {
        $this->connection = null;
    }
}
