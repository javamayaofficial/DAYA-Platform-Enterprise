<?php

declare(strict_types=1);

namespace App\Modules\Content\Models;

use App\Core\Modular\BaseModel;

final class ContentPart extends BaseModel
{
    public function __construct(
        public int $id,
        public int $contentId,
        public string $title,
        public string $summary,
        public string $body,
        public string $mediaUrl,
        public bool $isFreePreview,
        public int $sortOrder,
        public ?string $releaseAt,
        public ?string $deletedAt,
        public string $createdAt,
        public string $updatedAt
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) self::value($row, 'id', 0),
            (int) self::value($row, 'content_id', 0),
            (string) self::value($row, 'title', ''),
            (string) self::value($row, 'summary', ''),
            (string) self::value($row, 'body', ''),
            (string) self::value($row, 'media_url', ''),
            (bool) self::value($row, 'is_free_preview', false),
            (int) self::value($row, 'sort_order', 0),
            self::value($row, 'release_at'),
            self::value($row, 'deleted_at'),
            (string) self::value($row, 'created_at', ''),
            (string) self::value($row, 'updated_at', '')
        );
    }
}
