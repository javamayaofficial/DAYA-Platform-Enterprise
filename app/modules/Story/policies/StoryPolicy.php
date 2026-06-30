<?php

declare(strict_types=1);

namespace App\Modules\Story\Policies;

use App\Modules\Story\Models\Story;

final class StoryPolicy
{
    public function canCreate(array $auth, bool $hasCreatorProfile): bool
    {
        return (int) ($auth['user_id'] ?? 0) > 0 && $hasCreatorProfile;
    }

    public function canManageOwn(array $auth, Story $story, ?int $creatorId = null): bool
    {
        if ($this->canViewAdmin($auth)) {
            return true;
        }

        return $creatorId !== null && $creatorId === $story->creatorId;
    }

    public function canViewAdmin(array $auth): bool
    {
        $roles = array_map('strval', (array) ($auth['roles'] ?? []));
        $permissions = array_map('strval', (array) ($auth['permissions'] ?? []));

        return in_array('super_admin', $roles, true)
            || in_array('admin_yayasan', $roles, true)
            || in_array('story.admin.view', $permissions, true)
            || in_array('platform.admin.access', $permissions, true);
    }

    public function canViewPublic(Story $story): bool
    {
        $isPublished = $story->status === 'published';
        $isScheduledActive = $story->status === 'scheduled'
            && $story->publishedAt !== null
            && strtotime($story->publishedAt) !== false
            && strtotime($story->publishedAt) <= time();

        return $story->deletedAt === null
            && $story->visibility === 'public'
            && ($isPublished || $isScheduledActive);
    }
}
