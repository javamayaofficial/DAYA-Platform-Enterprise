<?php

declare(strict_types=1);

namespace App\Modules\Creator\Models;

use App\Core\Modular\BaseModel;

final class CreatorSocialLink extends BaseModel
{
    public function __construct(
        public int $id,
        public int $creatorId,
        public string $platform,
        public string $url,
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
            (string) self::value($row, 'platform', ''),
            (string) self::value($row, 'url', ''),
            (int) self::value($row, 'sort_order', 0),
            self::value($row, 'deleted_at'),
            (string) self::value($row, 'created_at', ''),
            (string) self::value($row, 'updated_at', '')
        );
    }
}
