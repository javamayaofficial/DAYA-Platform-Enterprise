<?php

declare(strict_types=1);

namespace App\Modules\Collection\Models;

use App\Core\Modular\BaseModel;

final class CollectionItem extends BaseModel
{
    public function __construct(
        public int $id,
        public int $collectionId,
        public int $contentId,
        public int $sortOrder,
        public ?string $deletedAt,
        public string $createdAt,
        public string $updatedAt,
        public ?string $contentCode = null,
        public ?string $contentTitle = null,
        public ?string $contentSlug = null,
        public ?string $contentExcerpt = null,
        public ?string $coverImageUrl = null,
        public ?string $contentStatus = null,
        public ?string $contentVisibility = null
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) self::value($row, 'id', 0),
            (int) self::value($row, 'collection_id', 0),
            (int) self::value($row, 'content_id', 0),
            (int) self::value($row, 'sort_order', 0),
            self::value($row, 'deleted_at'),
            (string) self::value($row, 'created_at', ''),
            (string) self::value($row, 'updated_at', ''),
            self::value($row, 'content_code'),
            self::value($row, 'content_title'),
            self::value($row, 'content_slug'),
            self::value($row, 'content_excerpt'),
            self::value($row, 'cover_image_url'),
            self::value($row, 'content_status'),
            self::value($row, 'content_visibility')
        );
    }
}
