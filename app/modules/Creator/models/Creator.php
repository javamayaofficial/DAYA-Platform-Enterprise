<?php

declare(strict_types=1);

namespace App\Modules\Creator\Models;

use App\Core\Modular\BaseModel;

final class Creator extends BaseModel
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $creatorCode,
        public string $slug,
        public string $handle,
        public string $displayName,
        public string $creatorType,
        public string $creatorLevel,
        public int $creatorRank,
        public string $verificationStatus,
        public string $tagline,
        public string $bio,
        public string $category,
        public string $location,
        public string $avatarUrl,
        public string $coverImageUrl,
        public string $websiteUrl,
        public string $publicEmail,
        public string $seoTitle,
        public string $seoDescription,
        public string $status,
        public string $kycStatus,
        public bool $publicPageEnabled,
        public bool $allowPublicContact,
        public bool $showPortfolioPublicly,
        public int $profileViewCount,
        public ?string $approvedAt,
        public ?int $approvedByUserId,
        public ?string $rejectedAt,
        public ?int $rejectedByUserId,
        public ?string $reviewNotes,
        public ?string $deletedAt,
        public string $createdAt,
        public string $updatedAt,
        public ?string $userName = null,
        public ?string $userEmail = null,
        public array $categories = [],
        public array $skills = [],
        public array $socialLinks = [],
        public array $portfolioItems = [],
        public array $achievements = [],
        public array $badges = [],
        public ?array $statistics = null,
        public ?array $application = null
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) self::value($row, 'id', 0),
            (int) self::value($row, 'user_id', 0),
            (string) self::value($row, 'creator_code', ''),
            (string) self::value($row, 'slug', ''),
            (string) self::value($row, 'handle', ''),
            (string) self::value($row, 'display_name', ''),
            (string) self::value($row, 'creator_type', 'individual'),
            (string) self::value($row, 'creator_level', 'emerging'),
            (int) self::value($row, 'creator_rank', 0),
            (string) self::value($row, 'verification_status', 'unverified'),
            (string) self::value($row, 'tagline', ''),
            (string) self::value($row, 'bio', ''),
            (string) self::value($row, 'category', ''),
            (string) self::value($row, 'location', ''),
            (string) self::value($row, 'avatar_url', ''),
            (string) self::value($row, 'cover_image_url', ''),
            (string) self::value($row, 'website_url', ''),
            (string) self::value($row, 'public_email', ''),
            (string) self::value($row, 'seo_title', ''),
            (string) self::value($row, 'seo_description', ''),
            (string) self::value($row, 'status', 'draft'),
            (string) self::value($row, 'kyc_status', 'unsubmitted'),
            (bool) self::value($row, 'public_page_enabled', false),
            (bool) self::value($row, 'allow_public_contact', false),
            (bool) self::value($row, 'show_portfolio_publicly', false),
            (int) self::value($row, 'profile_view_count', 0),
            self::value($row, 'approved_at'),
            self::value($row, 'approved_by_user_id') !== null ? (int) self::value($row, 'approved_by_user_id') : null,
            self::value($row, 'rejected_at'),
            self::value($row, 'rejected_by_user_id') !== null ? (int) self::value($row, 'rejected_by_user_id') : null,
            self::value($row, 'review_notes'),
            self::value($row, 'deleted_at'),
            (string) self::value($row, 'created_at', ''),
            (string) self::value($row, 'updated_at', ''),
            self::value($row, 'user_name'),
            self::value($row, 'user_email')
        );
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->deletedAt === null;
    }

    public function publicUrl(): string
    {
        return '/creators/' . $this->slug;
    }
}
