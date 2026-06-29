<?php

declare(strict_types=1);

namespace App\Modules\Creator\Models;

use App\Core\Modular\BaseModel;

final class CreatorPortfolioItem extends BaseModel
{
    public function __construct(
        public int $id,
        public int $creatorId,
        public string $portfolioType,
        public string $title,
        public string $summary,
        public string $organization,
        public string $issuedAt,
        public string $endedAt,
        public string $url,
        public string $thumbnailUrl,
        public bool $isFeatured,
        public int $sortOrder,
        public ?string $deletedAt,
        public string $createdAt,
        public string $updatedAt
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) self::value($row, 'id', 0),
            (int) self::value($row, 'creator_id', 0),
            (string) self::value($row, 'portfolio_type', 'story'),
            (string) self::value($row, 'title', ''),
            (string) self::value($row, 'summary', ''),
            (string) self::value($row, 'organization', ''),
            (string) self::value($row, 'issued_at', ''),
            (string) self::value($row, 'ended_at', ''),
            (string) self::value($row, 'url', ''),
            (string) self::value($row, 'thumbnail_url', ''),
            (bool) self::value($row, 'is_featured', false),
            (int) self::value($row, 'sort_order', 0),
            self::value($row, 'deleted_at'),
            (string) self::value($row, 'created_at', ''),
            (string) self::value($row, 'updated_at', '')
        );
    }
}
