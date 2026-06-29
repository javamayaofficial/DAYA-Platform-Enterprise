<?php

declare(strict_types=1);

namespace App\Modules\Creator\Models;

use App\Core\Modular\BaseModel;

final class CreatorApplication extends BaseModel
{
    public function __construct(
        public int $id,
        public int $creatorId,
        public string $status,
        public string $applicationNote,
        public string $kycFullName,
        public string $kycDocumentType,
        public string $kycDocumentNumber,
        public string $kycDocumentUrl,
        public ?string $submittedAt,
        public ?string $reviewedAt,
        public ?int $reviewedByUserId,
        public ?string $reviewNotes,
        public string $createdAt,
        public string $updatedAt
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) self::value($row, 'id', 0),
            (int) self::value($row, 'creator_id', 0),
            (string) self::value($row, 'status', 'pending_review'),
            (string) self::value($row, 'application_note', ''),
            (string) self::value($row, 'kyc_full_name', ''),
            (string) self::value($row, 'kyc_document_type', ''),
            (string) self::value($row, 'kyc_document_number', ''),
            (string) self::value($row, 'kyc_document_url', ''),
            self::value($row, 'submitted_at'),
            self::value($row, 'reviewed_at'),
            self::value($row, 'reviewed_by_user_id') !== null ? (int) self::value($row, 'reviewed_by_user_id') : null,
            self::value($row, 'review_notes'),
            (string) self::value($row, 'created_at', ''),
            (string) self::value($row, 'updated_at', '')
        );
    }
}
