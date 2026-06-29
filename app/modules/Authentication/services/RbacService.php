<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Services;

use App\Modules\Authentication\Models\RoleRepository;

final class RbacService
{
    public function __construct(private readonly RoleRepository $roleRepository)
    {
    }

    public function resolveAuthContext(int $userId): array
    {
        $roles = $this->roleRepository->getRoleSlugsForUser($userId);
        $permissions = $this->roleRepository->getPermissionSlugsForUser($userId);

        return [
            'roles' => $roles,
            'permissions' => $permissions,
        ];
    }

    public function hasRole(array $auth, string $role): bool
    {
        $roles = $auth['roles'] ?? [];

        return is_array($roles) && in_array($role, $roles, true);
    }

    public function hasPermission(array $auth, string $permission): bool
    {
        $permissions = $auth['permissions'] ?? [];
        if (is_array($permissions) && in_array($permission, $permissions, true)) {
            return true;
        }

        return $this->hasRole($auth, 'super_admin');
    }
}
