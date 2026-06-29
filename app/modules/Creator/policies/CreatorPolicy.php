<?php

declare(strict_types=1);

namespace App\Modules\Creator\Policies;

use App\Modules\Creator\Models\Creator;

final class CreatorPolicy
{
    public function canCreate(array $auth, ?Creator $existingCreator): bool
    {
        return $this->userId($auth) > 0 && $existingCreator === null;
    }

    public function canManageOwn(array $auth, Creator $creator): bool
    {
        if ($this->isAdmin($auth)) {
            return true;
        }

        return $this->userId($auth) === $creator->userId;
    }

    public function canDelete(array $auth, Creator $creator): bool
    {
        return $this->canManageOwn($auth, $creator);
    }

    public function canReview(array $auth): bool
    {
        return $this->isAdmin($auth);
    }

    public function canViewAdmin(array $auth): bool
    {
        return $this->isAdmin($auth);
    }

    public function canViewPublic(Creator $creator): bool
    {
        return $creator->status === 'active'
            && $creator->publicPageEnabled
            && $creator->deletedAt === null;
    }

    private function isAdmin(array $auth): bool
    {
        $roles = is_array($auth['roles'] ?? null) ? $auth['roles'] : [];
        $permissions = is_array($auth['permissions'] ?? null) ? $auth['permissions'] : [];

        return in_array('super_admin', $roles, true)
            || in_array('admin_yayasan', $roles, true)
            || in_array('creator.admin.view', $permissions, true)
            || in_array('creator.admin.review', $permissions, true)
            || in_array('platform.admin.access', $permissions, true);
    }

    private function userId(array $auth): int
    {
        return (int) ($auth['user_id'] ?? 0);
    }
}
