<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Models;

use PDO;

final class SessionRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function createRememberToken(int $userId, string $selector, string $validatorHash, string $expiresAt): void
    {
        $statement = $this->pdo->prepare('INSERT INTO remember_tokens (user_id, selector, validator_hash, expires_at, created_at) VALUES (:user_id, :selector, :validator_hash, :expires_at, NOW())');
        $statement->execute([
            'user_id' => $userId,
            'selector' => $selector,
            'validator_hash' => $validatorHash,
            'expires_at' => $expiresAt,
        ]);
    }

    public function findRememberToken(string $selector): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM remember_tokens WHERE selector = :selector AND revoked_at IS NULL LIMIT 1');
        $statement->execute(['selector' => $selector]);
        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    public function revokeRememberToken(int $id): void
    {
        $statement = $this->pdo->prepare('UPDATE remember_tokens SET revoked_at = NOW() WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    public function revokeAllRememberTokensByUser(int $userId): void
    {
        $statement = $this->pdo->prepare('UPDATE remember_tokens SET revoked_at = NOW() WHERE user_id = :user_id AND revoked_at IS NULL');
        $statement->execute(['user_id' => $userId]);
    }

    public function createDeviceSession(array $data): int
    {
        $statement = $this->pdo->prepare('INSERT INTO device_sessions (user_id, session_id, remember_selector, ip_address, user_agent, device_label, is_current, remember_me, last_activity_at, expires_at, created_at, updated_at) VALUES (:user_id, :session_id, :remember_selector, :ip_address, :user_agent, :device_label, :is_current, :remember_me, NOW(), :expires_at, NOW(), NOW())');
        $statement->execute([
            'user_id' => $data['user_id'],
            'session_id' => $data['session_id'],
            'remember_selector' => $data['remember_selector'],
            'ip_address' => $data['ip_address'],
            'user_agent' => $data['user_agent'],
            'device_label' => $data['device_label'],
            'is_current' => $data['is_current'],
            'remember_me' => $data['remember_me'],
            'expires_at' => $data['expires_at'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function touchDeviceSession(string $sessionId): void
    {
        $statement = $this->pdo->prepare('UPDATE device_sessions SET last_activity_at = NOW(), is_current = 1, updated_at = NOW() WHERE session_id = :session_id AND revoked_at IS NULL');
        $statement->execute(['session_id' => $sessionId]);
    }

    public function clearCurrentMarker(int $userId): void
    {
        $statement = $this->pdo->prepare('UPDATE device_sessions SET is_current = 0, updated_at = NOW() WHERE user_id = :user_id');
        $statement->execute(['user_id' => $userId]);
    }

    public function getDeviceSessionsByUser(int $userId): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM device_sessions WHERE user_id = :user_id AND revoked_at IS NULL ORDER BY is_current DESC, last_activity_at DESC');
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll() ?: [];
    }

    public function revokeDeviceSessionBySessionId(string $sessionId): void
    {
        $statement = $this->pdo->prepare('UPDATE device_sessions SET revoked_at = NOW(), is_current = 0, updated_at = NOW() WHERE session_id = :session_id');
        $statement->execute(['session_id' => $sessionId]);
    }

    public function revokeDeviceSessionByIdForUser(int $userId, int $deviceSessionId): void
    {
        $statement = $this->pdo->prepare('UPDATE device_sessions SET revoked_at = NOW(), is_current = 0, updated_at = NOW() WHERE id = :id AND user_id = :user_id');
        $statement->execute([
            'id' => $deviceSessionId,
            'user_id' => $userId,
        ]);
    }
}
