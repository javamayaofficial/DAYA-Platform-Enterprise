<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    private static array $connections = [];

    public static function hasConfiguration(): bool
    {
        $config = Config::get('database.connections.mysql', []);

        return (string) ($config['host'] ?? '') !== ''
            && (string) ($config['database'] ?? '') !== ''
            && (string) ($config['username'] ?? '') !== '';
    }

    public static function connection(?string $name = null): PDO
    {
        $name ??= (string) Config::get('database.default', 'mysql');

        if (isset(self::$connections[$name])) {
            return self::$connections[$name];
        }

        $config = Config::get('database.connections.' . $name, []);
        if ($config === [] || !self::hasConfiguration()) {
            throw new RuntimeException('Database belum dikonfigurasi.');
        }

        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $config['driver'] ?? 'mysql',
            $config['host'] ?? '127.0.0.1',
            (int) ($config['port'] ?? 3306),
            $config['database'] ?? '',
            $config['charset'] ?? 'utf8mb4'
        );

        try {
            $pdo = new PDO($dsn, (string) ($config['username'] ?? ''), (string) ($config['password'] ?? ''), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException('Koneksi database gagal: ' . $exception->getMessage(), 0, $exception);
        }

        self::$connections[$name] = $pdo;

        return $pdo;
    }

    public static function test(array $connection): void
    {
        $dsn = sprintf(
            '%s:host=%s;port=%d;dbname=%s;charset=%s',
            $connection['driver'] ?? 'mysql',
            $connection['host'] ?? '127.0.0.1',
            (int) ($connection['port'] ?? 3306),
            $connection['database'] ?? '',
            $connection['charset'] ?? 'utf8mb4'
        );

        new PDO($dsn, (string) ($connection['username'] ?? ''), (string) ($connection['password'] ?? ''), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}
