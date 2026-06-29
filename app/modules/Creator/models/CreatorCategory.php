<?php

declare(strict_types=1);

namespace App\Modules\Creator\Models;

use App\Core\Modular\BaseModel;

final class CreatorCategory extends BaseModel
{
    public function __construct(
        public int $id,
        public int $creatorId,
        public string $name,
        public int $sortOrder,
        public string $createdAt
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) self::value($row, 'id', 0),
            (int) self::value($row, 'creator_id', 0),
            (string) self::value($row, 'name', ''),
            (int) self::value($row, 'sort_order', 0),
            (string) self::value($row, 'created_at', '')
        );
    }
}
