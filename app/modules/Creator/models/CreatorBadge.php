<?php

declare(strict_types=1);

namespace App\Modules\Creator\Models;

use App\Core\Modular\BaseModel;

final class CreatorBadge extends BaseModel
{
    public function __construct(
        public int $id,
        public int $creatorId,
        public string $badgeKey,
        public string $badgeLabel,
        public string $assignedAt
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) self::value($row, 'id', 0),
            (int) self::value($row, 'creator_id', 0),
            (string) self::value($row, 'badge_key', ''),
            (string) self::value($row, 'badge_label', ''),
            (string) self::value($row, 'assigned_at', '')
        );
    }
}
