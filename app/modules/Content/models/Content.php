<?php

declare(strict_types=1);

namespace App\Modules\Content\Models;

use App\Core\Modular\BaseModel;

final class Content extends BaseModel
{
    public function __construct(
        public int $id,
        public int $creatorId,
        public string $contentCode,
        public string $contentType,
        public string $title,
        public string $slug,
        public string $excerpt,
        public string $body,
        public string $coverImageUrl,
        public string $seoTitle,
        public string $seoDescription,
        public string $accessPolicy,
        public int $priceMinor,
        public string $currencyCode,
        public string $visibility,
        public string $status,
        public int $viewsCount,
        public int $likesCount,
        public int $commentsCount,
        public int $sharesCount,
        public int $sponsorCount,
        public int $donationCount,
        public int $affiliateCount,
        public int $revenueMinor,
        public int $recommendationScore,
        public ?string $publishedAt,
        public ?string $reviewedAt,
        public ?int $reviewedByUserId,
        public string $reviewNotes,
        public ?string $deletedAt,
        public string $createdAt,
        public string $updatedAt,
        public ?string $creatorDisplayName = null,
        public ?string $creatorSlug = null,
        public ?string $creatorCode = null,
        public array $parts = []
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) self::value($row, 'id', 0),
            (int) self::value($row, 'creator_id', 0),
            (string) self::value($row, 'content_code', ''),
            (string) self::value($row, 'content_type', 'story'),
            (string) self::value($row, 'title', ''),
            (string) self::value($row, 'slug', ''),
            (string) self::value($row, 'excerpt', ''),
            (string) self::value($row, 'body', ''),
            (string) self::value($row, 'cover_image_url', ''),
            (string) self::value($row, 'seo_title', ''),
            (string) self::value($row, 'seo_description', ''),
            (string) self::value($row, 'access_policy', 'free'),
            (int) self::value($row, 'price_minor', 0),
            (string) self::value($row, 'currency_code', 'IDR'),
            (string) self::value($row, 'visibility', 'public'),
            (string) self::value($row, 'status', 'draft'),
            (int) self::value($row, 'views_count', 0),
            (int) self::value($row, 'likes_count', 0),
            (int) self::value($row, 'comments_count', 0),
            (int) self::value($row, 'shares_count', 0),
            (int) self::value($row, 'sponsor_count', 0),
            (int) self::value($row, 'donation_count', 0),
            (int) self::value($row, 'affiliate_count', 0),
            (int) self::value($row, 'revenue_minor', 0),
            (int) self::value($row, 'recommendation_score', 0),
            self::value($row, 'published_at'),
            self::value($row, 'reviewed_at'),
            self::value($row, 'reviewed_by_user_id') !== null ? (int) self::value($row, 'reviewed_by_user_id') : null,
            (string) self::value($row, 'review_notes', ''),
            self::value($row, 'deleted_at'),
            (string) self::value($row, 'created_at', ''),
            (string) self::value($row, 'updated_at', ''),
            self::value($row, 'creator_display_name'),
            self::value($row, 'creator_slug'),
            self::value($row, 'creator_code')
        );
    }

    public function publicUrl(): string
    {
        return '/contents/' . $this->slug;
    }
}
