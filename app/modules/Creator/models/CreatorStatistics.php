<?php

declare(strict_types=1);

namespace App\Modules\Creator\Models;

use App\Core\Modular\BaseModel;

final class CreatorStatistics extends BaseModel
{
    public function __construct(
        public int $creatorId,
        public int $followersCount,
        public int $readsCount,
        public int $listensCount,
        public int $downloadsCount,
        public int $sponsorCount,
        public int $donationCount,
        public int $affiliateCount,
        public int $revenueMinor,
        public int $walletAvailableMinor,
        public int $walletPendingMinor,
        public string $updatedAt
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) self::value($row, 'creator_id', 0),
            (int) self::value($row, 'followers_count', 0),
            (int) self::value($row, 'reads_count', 0),
            (int) self::value($row, 'listens_count', 0),
            (int) self::value($row, 'downloads_count', 0),
            (int) self::value($row, 'sponsor_count', 0),
            (int) self::value($row, 'donation_count', 0),
            (int) self::value($row, 'affiliate_count', 0),
            (int) self::value($row, 'revenue_minor', 0),
            (int) self::value($row, 'wallet_available_minor', 0),
            (int) self::value($row, 'wallet_pending_minor', 0),
            (string) self::value($row, 'updated_at', '')
        );
    }

    public function toArray(): array
    {
        return [
            'followers_count' => $this->followersCount,
            'reads_count' => $this->readsCount,
            'listens_count' => $this->listensCount,
            'downloads_count' => $this->downloadsCount,
            'sponsor_count' => $this->sponsorCount,
            'donation_count' => $this->donationCount,
            'affiliate_count' => $this->affiliateCount,
            'revenue_minor' => $this->revenueMinor,
            'wallet_available_minor' => $this->walletAvailableMinor,
            'wallet_pending_minor' => $this->walletPendingMinor,
            'updated_at' => $this->updatedAt,
        ];
    }
}
