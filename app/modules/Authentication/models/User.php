<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Models;

use App\Core\Modular\BaseModel;

final class User extends BaseModel
{
    public function __construct(
        public int $id,
        public string $uuid,
        public string $name,
        public string $email,
        public string $passwordHash,
        public string $status,
        public ?string $emailVerifiedAt,
        public ?string $lastLoginAt,
        public string $createdAt,
        public string $updatedAt
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) self::value($row, 'id', 0),
            (string) self::value($row, 'uuid', ''),
            (string) self::value($row, 'name', ''),
            (string) self::value($row, 'email', ''),
            (string) self::value($row, 'password_hash', ''),
            (string) self::value($row, 'status', ''),
            isset($row['email_verified_at']) ? (string) self::value($row, 'email_verified_at') : null,
            isset($row['last_login_at']) ? (string) self::value($row, 'last_login_at') : null,
            (string) self::value($row, 'created_at', ''),
            (string) self::value($row, 'updated_at', '')
        );
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPendingVerification(): bool
    {
        return $this->status === 'pending_verification';
    }
}
