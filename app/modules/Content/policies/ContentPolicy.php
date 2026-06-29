<?php

declare(strict_types=1);

namespace App\Modules\Content\Policies;

use App\Modules\Content\Models\Content;

final class ContentPolicy
{
    public function canCreate(array $auth, bool $hasCreatorProfile): bool
    {
        return (int) ($auth['user_id'] ?? 0) > 0 && $hasCreatorProfile;
    }

    public function canManageOwn(array $auth, Content $content, ?int $creatorId = null): bool
    {
        if ($this->canViewAdmin($auth)) {
            return true;
        }

        return $creatorId !== null && $creatorId === $content->creatorId;
    }

    public function canReview(array $auth): bool
    {
        $roles = array_map('strval', (array) ($auth['roles'] ?? []));
        $permissions = array_map('strval', (array) ($auth['permissions'] ?? []));

        return in_array('super_admin', $roles, true)
            || in_array('admin_yayasan', $roles, true)
            || in_array('content.admin.review', $permissions, true)
            || in_array('platform.admin.access', $permissions, true);
    }

    public function canViewAdmin(array $auth): bool
    {
        $roles = array_map('strval', (array) ($auth['roles'] ?? []));
        $permissions = array_map('strval', (array) ($auth['permissions'] ?? []));

        return in_array('super_admin', $roles, true)
            || in_array('admin_yayasan', $roles, true)
            || in_array('content.admin.view', $permissions, true)
            || in_array('platform.admin.access', $permissions, true);
    }

    public function canViewPublic(Content $content): bool
    {
        return $content->status === 'published'
            && $content->visibility === 'public'
            && $content->deletedAt === null;
    }
}
