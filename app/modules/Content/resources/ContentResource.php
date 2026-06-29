<?php

declare(strict_types=1);

namespace App\Modules\Content\Resources;

use App\Modules\Content\Models\Content;
use App\Modules\Content\Models\ContentPart;

final class ContentResource
{
    public static function detail(Content $content): array
    {
        return [
            'id' => $content->id,
            'creator_id' => $content->creatorId,
            'content_code' => $content->contentCode,
            'content_type' => $content->contentType,
            'title' => $content->title,
            'slug' => $content->slug,
            'public_url' => $content->publicUrl(),
            'excerpt' => $content->excerpt,
            'body' => $content->body,
            'cover_image_url' => $content->coverImageUrl,
            'seo_title' => $content->seoTitle,
            'seo_description' => $content->seoDescription,
            'access_policy' => $content->accessPolicy,
            'price_minor' => $content->priceMinor,
            'currency_code' => $content->currencyCode,
            'visibility' => $content->visibility,
            'status' => $content->status,
            'views_count' => $content->viewsCount,
            'likes_count' => $content->likesCount,
            'comments_count' => $content->commentsCount,
            'shares_count' => $content->sharesCount,
            'sponsor_count' => $content->sponsorCount,
            'donation_count' => $content->donationCount,
            'affiliate_count' => $content->affiliateCount,
            'revenue_minor' => $content->revenueMinor,
            'recommendation_score' => $content->recommendationScore,
            'published_at' => $content->publishedAt,
            'reviewed_at' => $content->reviewedAt,
            'reviewed_by_user_id' => $content->reviewedByUserId,
            'review_notes' => $content->reviewNotes,
            'deleted_at' => $content->deletedAt,
            'created_at' => $content->createdAt,
            'updated_at' => $content->updatedAt,
            'creator_display_name' => $content->creatorDisplayName,
            'creator_slug' => $content->creatorSlug,
            'creator_code' => $content->creatorCode,
            'parts' => array_map([self::class, 'part'], $content->parts),
        ];
    }

    public static function listItem(Content $content): array
    {
        return [
            'id' => $content->id,
            'content_code' => $content->contentCode,
            'content_type' => $content->contentType,
            'title' => $content->title,
            'slug' => $content->slug,
            'public_url' => $content->publicUrl(),
            'excerpt' => $content->excerpt,
            'cover_image_url' => $content->coverImageUrl,
            'status' => $content->status,
            'visibility' => $content->visibility,
            'access_policy' => $content->accessPolicy,
            'price_minor' => $content->priceMinor,
            'views_count' => $content->viewsCount,
            'likes_count' => $content->likesCount,
            'comments_count' => $content->commentsCount,
            'shares_count' => $content->sharesCount,
            'creator_display_name' => $content->creatorDisplayName,
            'creator_slug' => $content->creatorSlug,
            'creator_code' => $content->creatorCode,
            'published_at' => $content->publishedAt,
            'deleted_at' => $content->deletedAt,
        ];
    }

    public static function part(ContentPart $part): array
    {
        return [
            'id' => $part->id,
            'title' => $part->title,
            'summary' => $part->summary,
            'body' => $part->body,
            'media_url' => $part->mediaUrl,
            'is_free_preview' => $part->isFreePreview,
            'sort_order' => $part->sortOrder,
            'release_at' => $part->releaseAt,
        ];
    }

    public static function statistics(Content $content): array
    {
        return [
            'views_count' => $content->viewsCount,
            'likes_count' => $content->likesCount,
            'comments_count' => $content->commentsCount,
            'shares_count' => $content->sharesCount,
            'sponsor_count' => $content->sponsorCount,
            'donation_count' => $content->donationCount,
            'affiliate_count' => $content->affiliateCount,
            'revenue_minor' => $content->revenueMinor,
            'recommendation_score' => $content->recommendationScore,
            'parts_count' => count($content->parts),
        ];
    }
}
