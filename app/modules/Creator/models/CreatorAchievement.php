<?php

declare(strict_types=1);

namespace App\Modules\Creator\Models;

use App\Core\Modular\BaseModel;

final class CreatorAchievement extends BaseModel
{
    public function __construct(
        public int $id,
        public int $creatorId,
        public string $title,
        public string $issuer,
        public string $description,
        public string $achievedAt,
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
            (string) self::value($row, 'title', ''),
            (string) self::value($row, 'issuer', ''),
            (string) self::value($row, 'description', ''),
            (string) self::value($row, 'achieved_at', ''),
            (string) self::value($row, 'url', ''),
            (int) self::value($row, 'sort_order', 0),
            self::value($row, 'deleted_at'),
            (string) self::value($row, 'created_at', ''),
            (string) self::value($row, 'updated_at', '')
        );
    }
}
