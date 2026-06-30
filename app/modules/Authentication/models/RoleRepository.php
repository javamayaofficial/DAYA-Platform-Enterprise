<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Models;

use PDO;

final class RoleRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function getRolesForUser(int $userId): array
    {
        $statement = $this->pdo->prepare('SELECT r.* FROM roles r INNER JOIN user_roles ur ON ur.role_id = r.id WHERE ur.user_id = :user_id ORDER BY r.name ASC');
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll() ?: [];
    }

    public function getRoleSlugsForUser(int $userId): array
    {
        return array_map(static fn (array $row): string => (string) $row['slug'], $this->getRolesForUser($userId));
    }

    public function getPermissionsForUser(int $userId): array
    {
        $sql = 'SELECT DISTINCT p.*
                FROM permissions p
                INNER JOIN role_permissions rp ON rp.permission_id = p.id
                INNER JOIN user_roles ur ON ur.role_id = rp.role_id
                WHERE ur.user_id = :user_id
                ORDER BY p.name ASC';
        $statement = $this->pdo->prepare($sql);
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll() ?: [];
    }

    public function getPermissionSlugsForUser(int $userId): array
    {
        return array_map(static fn (array $row): string => (string) $row['slug'], $this->getPermissionsForUser($userId));
    }

    public function getAllRoles(): array
    {
        return $this->pdo->query('SELECT * FROM roles ORDER BY name ASC')->fetchAll() ?: [];
    }

    public function getAllPermissions(): array
    {
        return $this->pdo->query('SELECT * FROM permissions ORDER BY name ASC')->fetchAll() ?: [];
    }

    public function syncUserRoles(int $userId, array $roleSlugs): void
    {
        $roleSlugs = array_values(array_unique(array_filter(array_map('strval', $roleSlugs))));
        $this->pdo->prepare('DELETE FROM user_roles WHERE user_id = :user_id')->execute(['user_id' => $userId]);

        if ($roleSlugs === []) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($roleSlugs), '?'));
        $statement = $this->pdo->prepare('SELECT id, slug FROM roles WHERE slug IN (' . $placeholders . ')');
        $statement->execute($roleSlugs);
        $roles = $statement->fetchAll() ?: [];

        $insert = $this->pdo->prepare('INSERT INTO user_roles (user_id, role_id, created_at) VALUES (:user_id, :role_id, NOW())');
        foreach ($roles as $role) {
            $insert->execute([
                'user_id' => $userId,
                'role_id' => (int) $role['id'],
            ]);
        }
    }

    public function getRolePermissionMatrix(): array
    {
        $sql = 'SELECT r.id AS role_id, r.slug AS role_slug, r.name AS role_name, p.slug AS permission_slug, p.name AS permission_name
                FROM roles r
                LEFT JOIN role_permissions rp ON rp.role_id = r.id
                LEFT JOIN permissions p ON p.id = rp.permission_id
                ORDER BY r.name ASC, p.name ASC';

        return $this->pdo->query($sql)->fetchAll() ?: [];
    }

    public function userHasPermission(int $userId, string $permission): bool
    {
        $sql = 'SELECT COUNT(*) FROM permissions p
                INNER JOIN role_permissions rp ON rp.permission_id = p.id
                INNER JOIN user_roles ur ON ur.role_id = rp.role_id
                WHERE ur.user_id = :user_id AND p.slug = :permission';
        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'user_id' => $userId,
            'permission' => $permission,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }
}
