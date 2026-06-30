<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Models;

use PDO;

final class TokenRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function createEmailVerificationToken(int $userId, string $tokenHash, string $expiresAt): void
    {
        $this->deleteEmailVerificationTokens($userId);
        $statement = $this->pdo->prepare('INSERT INTO email_verification_tokens (user_id, token_hash, expires_at, created_at) VALUES (:user_id, :token_hash, :expires_at, NOW())');
        $statement->execute([
            'user_id' => $userId,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
        ]);
    }

    public function findEmailVerificationToken(string $tokenHash): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM email_verification_tokens WHERE token_hash = :token_hash AND used_at IS NULL LIMIT 1');
        $statement->execute(['token_hash' => $tokenHash]);
        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    public function markEmailVerificationTokenUsed(int $id): void
    {
        $statement = $this->pdo->prepare('UPDATE email_verification_tokens SET used_at = NOW() WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    public function deleteEmailVerificationTokens(int $userId): void
    {
        $statement = $this->pdo->prepare('DELETE FROM email_verification_tokens WHERE user_id = :user_id');
        $statement->execute(['user_id' => $userId]);
    }

    public function createPasswordResetToken(int $userId, string $tokenHash, string $expiresAt): void
    {
        $this->deletePasswordResetTokens($userId);
        $statement = $this->pdo->prepare('INSERT INTO password_reset_tokens (user_id, token_hash, expires_at, created_at) VALUES (:user_id, :token_hash, :expires_at, NOW())');
        $statement->execute([
            'user_id' => $userId,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
        ]);
    }

    public function findPasswordResetToken(string $tokenHash): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM password_reset_tokens WHERE token_hash = :token_hash AND used_at IS NULL LIMIT 1');
        $statement->execute(['token_hash' => $tokenHash]);
        $row = $statement->fetch();

        return is_array($row) ? $row : null;
    }

    public function markPasswordResetTokenUsed(int $id): void
    {
        $statement = $this->pdo->prepare('UPDATE password_reset_tokens SET used_at = NOW() WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    public function deletePasswordResetTokens(int $userId): void
    {
        $statement = $this->pdo->prepare('DELETE FROM password_reset_tokens WHERE user_id = :user_id');
        $statement->execute(['user_id' => $userId]);
    }
}
