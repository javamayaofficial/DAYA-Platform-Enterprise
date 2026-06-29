<?php

declare(strict_types=1);

namespace App\Modules\Collection\Models;

use App\Core\Modular\BaseModel;

final class Collection extends BaseModel
{
    public function __construct(
        public int $id,
        public int $creatorId,
        public string $collectionCode,
        public string $title,
        public string $slug,
        public string $description,
        public string $coverImageUrl,
        public string $visibility,
        public string $status,
        public ?string $publishedAt,
        public ?string $deletedAt,
        public string $createdAt,
        public string $updatedAt,
        public ?string $creatorDisplayName = null,
        public ?string $creatorSlug = null,
        public ?string $creatorCode = null,
        public array $items = []
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) self::value($row, 'id', 0),
            (int) self::value($row, 'creator_id', 0),
            (string) self::value($row, 'collection_code', ''),
            (string) self::value($row, 'title', ''),
            (string) self::value($row, 'slug', ''),
            (string) self::value($row, 'description', ''),
            (string) self::value($row, 'cover_image_url', ''),
            (string) self::value($row, 'visibility', 'public'),
            (string) self::value($row, 'status', 'draft'),
            self::value($row, 'published_at'),
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
        return '/collections/' . $this->slug;
    }
}
