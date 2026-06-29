<?php

declare(strict_types=1);

namespace App\Modules\Creator\Resources;

use App\Modules\Creator\Models\Creator;
use App\Modules\Creator\Models\CreatorAchievement;
use App\Modules\Creator\Models\CreatorBadge;
use App\Modules\Creator\Models\CreatorPortfolioItem;
use App\Modules\Creator\Models\CreatorStatistics;
use App\Modules\Creator\Models\CreatorCategory;
use App\Modules\Creator\Models\CreatorSkill;
use App\Modules\Creator\Models\CreatorSocialLink;

final class CreatorResource
{
    public static function detail(Creator $creator): array
    {
        return [
            'id' => $creator->id,
            'user_id' => $creator->userId,
            'creator_code' => $creator->creatorCode,
            'slug' => $creator->slug,
            'public_url' => $creator->publicUrl(),
            'handle' => $creator->handle,
            'display_name' => $creator->displayName,
            'creator_type' => $creator->creatorType,
            'creator_level' => $creator->creatorLevel,
            'creator_rank' => $creator->creatorRank,
            'verification_status' => $creator->verificationStatus,
            'tagline' => $creator->tagline,
            'bio' => $creator->bio,
            'category' => $creator->category,
            'location' => $creator->location,
            'avatar_url' => $creator->avatarUrl,
            'cover_image_url' => $creator->coverImageUrl,
            'website_url' => $creator->websiteUrl,
            'public_email' => $creator->publicEmail,
            'seo_title' => $creator->seoTitle,
            'seo_description' => $creator->seoDescription,
            'status' => $creator->status,
            'kyc_status' => $creator->kycStatus,
            'public_page_enabled' => $creator->publicPageEnabled,
            'allow_public_contact' => $creator->allowPublicContact,
            'show_portfolio_publicly' => $creator->showPortfolioPublicly,
            'profile_view_count' => $creator->profileViewCount,
            'approved_at' => $creator->approvedAt,
            'approved_by_user_id' => $creator->approvedByUserId,
            'rejected_at' => $creator->rejectedAt,
            'rejected_by_user_id' => $creator->rejectedByUserId,
            'review_notes' => $creator->reviewNotes,
            'deleted_at' => $creator->deletedAt,
            'created_at' => $creator->createdAt,
            'updated_at' => $creator->updatedAt,
            'user_name' => $creator->userName,
            'user_email' => $creator->userEmail,
            'categories' => array_map([self::class, 'category'], $creator->categories),
            'skills' => array_map([self::class, 'skill'], $creator->skills),
            'social_links' => array_map([self::class, 'socialLink'], $creator->socialLinks),
            'portfolio_items' => array_map([self::class, 'portfolioItem'], $creator->portfolioItems),
            'achievements' => array_map([self::class, 'achievement'], $creator->achievements),
            'badges' => array_map([self::class, 'badge'], $creator->badges),
            'statistics' => $creator->statistics,
            'application' => $creator->application,
        ];
    }

    public static function listItem(Creator $creator): array
    {
        return [
            'id' => $creator->id,
            'creator_code' => $creator->creatorCode,
            'slug' => $creator->slug,
            'public_url' => $creator->publicUrl(),
            'handle' => $creator->handle,
            'display_name' => $creator->displayName,
            'creator_type' => $creator->creatorType,
            'creator_level' => $creator->creatorLevel,
            'creator_rank' => $creator->creatorRank,
            'verification_status' => $creator->verificationStatus,
            'category' => $creator->category,
            'status' => $creator->status,
            'kyc_status' => $creator->kycStatus,
            'profile_view_count' => $creator->profileViewCount,
            'public_page_enabled' => $creator->publicPageEnabled,
            'user_name' => $creator->userName,
            'user_email' => $creator->userEmail,
            'deleted_at' => $creator->deletedAt,
            'updated_at' => $creator->updatedAt,
        ];
    }

    public static function category(CreatorCategory $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'sort_order' => $category->sortOrder,
        ];
    }

    public static function skill(CreatorSkill $skill): array
    {
        return [
            'id' => $skill->id,
            'name' => $skill->name,
            'sort_order' => $skill->sortOrder,
        ];
    }

    public static function socialLink(CreatorSocialLink $link): array
    {
        return [
            'id' => $link->id,
            'platform' => $link->platform,
            'url' => $link->url,
            'sort_order' => $link->sortOrder,
        ];
    }

    public static function portfolioItem(CreatorPortfolioItem $item): array
    {
        return [
            'id' => $item->id,
            'portfolio_type' => $item->portfolioType,
            'title' => $item->title,
            'summary' => $item->summary,
            'organization' => $item->organization,
            'issued_at' => $item->issuedAt,
            'ended_at' => $item->endedAt,
            'url' => $item->url,
            'thumbnail_url' => $item->thumbnailUrl,
            'is_featured' => $item->isFeatured,
            'sort_order' => $item->sortOrder,
        ];
    }

    public static function achievement(CreatorAchievement $achievement): array
    {
        return [
            'id' => $achievement->id,
            'title' => $achievement->title,
            'issuer' => $achievement->issuer,
            'description' => $achievement->description,
            'achieved_at' => $achievement->achievedAt,
            'url' => $achievement->url,
            'sort_order' => $achievement->sortOrder,
        ];
    }

    public static function badge(CreatorBadge $badge): array
    {
        return [
            'id' => $badge->id,
            'badge_key' => $badge->badgeKey,
            'badge_label' => $badge->badgeLabel,
            'assigned_at' => $badge->assignedAt,
        ];
    }

    public static function statisticsSnapshot(CreatorStatistics $statistics): array
    {
        return $statistics->toArray();
    }

    public static function statistics(Creator $creator): array
    {
        return [
            'profile_views' => $creator->profileViewCount,
            'followers_count' => (int) ($creator->statistics['followers_count'] ?? 0),
            'reads_count' => (int) ($creator->statistics['reads_count'] ?? 0),
            'listens_count' => (int) ($creator->statistics['listens_count'] ?? 0),
            'downloads_count' => (int) ($creator->statistics['downloads_count'] ?? 0),
            'sponsor_count' => (int) ($creator->statistics['sponsor_count'] ?? 0),
            'donation_count' => (int) ($creator->statistics['donation_count'] ?? 0),
            'affiliate_count' => (int) ($creator->statistics['affiliate_count'] ?? 0),
            'revenue_minor' => (int) ($creator->statistics['revenue_minor'] ?? 0),
            'wallet_available_minor' => (int) ($creator->statistics['wallet_available_minor'] ?? 0),
            'wallet_pending_minor' => (int) ($creator->statistics['wallet_pending_minor'] ?? 0),
            'social_links' => count($creator->socialLinks),
            'portfolio_items' => count($creator->portfolioItems),
            'achievements' => count($creator->achievements),
            'badges' => count($creator->badges),
            'skills' => count($creator->skills),
            'featured_portfolio_items' => count(array_filter(
                $creator->portfolioItems,
                static fn (CreatorPortfolioItem $item): bool => $item->isFeatured
            )),
        ];
    }
}
