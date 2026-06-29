<?php

declare(strict_types=1);

namespace App\Modules\Collection\Policies;

use App\Modules\Collection\Models\Collection;

final class CollectionPolicy
{
    public function canCreate(array $auth, bool $hasCreatorProfile): bool
    {
        return (int) ($auth['user_id'] ?? 0) > 0 && $hasCreatorProfile;
    }

    public function canManageOwn(array $auth, Collection $collection, ?int $creatorId = null): bool
    {
        if ($this->canViewAdmin($auth)) {
            return true;
        }

        return $creatorId !== null && $creatorId === $collection->creatorId;
    }

    public function canViewAdmin(array $auth): bool
    {
        $roles = array_map('strval', (array) ($auth['roles'] ?? []));
        $permissions = array_map('strval', (array) ($auth['permissions'] ?? []));

        return in_array('super_admin', $roles, true)
            || in_array('admin_yayasan', $roles, true)
            || in_array('collection.admin.view', $permissions, true)
            || in_array('platform.admin.access', $permissions, true);
    }

    public function canViewPublic(Collection $collection): bool
    {
        return $collection->status === 'published'
            && $collection->visibility === 'public'
            && $collection->deletedAt === null;
    }
}
