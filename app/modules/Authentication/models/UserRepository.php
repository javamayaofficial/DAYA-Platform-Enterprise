<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Models;

use App\Core\Modular\BaseRepository;
use PDO;

final class UserRepository extends BaseRepository
{
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    public function create(array $data): int
    {
        $statement = $this->prepare('INSERT INTO users (uuid, name, email, password_hash, status, email_verified_at, last_login_at, created_at, updated_at) VALUES (:uuid, :name, :email, :password_hash, :status, :email_verified_at, :last_login_at, NOW(), NOW())');
        $statement->execute([
            'uuid' => $data['uuid'],
            'name' => $data['name'],
            'email' => strtolower((string) $data['email']),
            'password_hash' => $data['password_hash'],
            'status' => $data['status'],
            'email_verified_at' => $data['email_verified_at'],
            'last_login_at' => $data['last_login_at'],
        ]);

        return $this->lastInsertId();
    }

    public function findById(int $id): ?User
    {
        $statement = $this->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return is_array($row) ? User::fromArray($row) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $statement = $this->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => strtolower($email)]);
        $row = $statement->fetch();

        return is_array($row) ? User::fromArray($row) : null;
    }

    public function updatePassword(int $userId, string $passwordHash): void
    {
        $statement = $this->prepare('UPDATE users SET password_hash = :password_hash, updated_at = NOW() WHERE id = :id');
        $statement->execute([
            'id' => $userId,
            'password_hash' => $passwordHash,
        ]);
    }

    public function markEmailVerified(int $userId): void
    {
        $statement = $this->prepare("UPDATE users SET email_verified_at = NOW(), status = 'active', updated_at = NOW() WHERE id = :id");
        $statement->execute(['id' => $userId]);
    }

    public function updateLastLogin(int $userId): void
    {
        $statement = $this->prepare('UPDATE users SET last_login_at = NOW(), updated_at = NOW() WHERE id = :id');
        $statement->execute(['id' => $userId]);
    }

    public function listWithRoles(): array
    {
        $sql = 'SELECT u.*, GROUP_CONCAT(r.name ORDER BY r.name SEPARATOR ", ") AS role_names, GROUP_CONCAT(r.slug ORDER BY r.slug SEPARATOR ",") AS role_slugs
                FROM users u
                LEFT JOIN user_roles ur ON ur.user_id = u.id
                LEFT JOIN roles r ON r.id = ur.role_id
                GROUP BY u.id
                ORDER BY u.created_at DESC';

        return $this->pdo()->query($sql)->fetchAll() ?: [];
    }
}
