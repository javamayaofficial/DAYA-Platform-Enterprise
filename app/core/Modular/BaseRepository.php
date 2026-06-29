<?php

declare(strict_types=1);

namespace App\Core\Modular;

use PDO;
use PDOStatement;

abstract class BaseRepository
{
    public function __construct(protected readonly PDO $pdo)
    {
    }

    protected function pdo(): PDO
    {
        return $this->pdo;
    }

    protected function prepare(string $sql): PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    protected function lastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }
}
