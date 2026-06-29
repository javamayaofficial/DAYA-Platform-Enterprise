<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Models;

use PDO;

final class LoginHistoryRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function record(array $data): void
    {
        $statement = $this->pdo->prepare('INSERT INTO login_history (user_id, attempted_email, ip_address, user_agent, status, failure_reason, created_at) VALUES (:user_id, :attempted_email, :ip_address, :user_agent, :status, :failure_reason, NOW())');
        $statement->execute([
            'user_id' => $data['user_id'],
            'attempted_email' => $data['attempted_email'],
            'ip_address' => $data['ip_address'],
            'user_agent' => $data['user_agent'],
            'status' => $data['status'],
            'failure_reason' => $data['failure_reason'],
        ]);
    }

    public function countRecentFailures(string $email, string $ipAddress, int $windowMinutes): int
    {
        $sinceAt = date('Y-m-d H:i:s', time() - ($windowMinutes * 60));
        $statement = $this->pdo->prepare('SELECT COUNT(*) FROM login_history WHERE attempted_email = :attempted_email AND ip_address = :ip_address AND status = :status AND created_at >= :since_at');
        $statement->execute([
            'attempted_email' => strtolower($email),
            'ip_address' => $ipAddress,
            'status' => 'failed',
            'since_at' => $sinceAt,
        ]);

        return (int) $statement->fetchColumn();
    }

    public function recentByUser(int $userId, int $limit = 50): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM login_history WHERE user_id = :user_id ORDER BY created_at DESC LIMIT ' . (int) $limit);
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll() ?: [];
    }
}
