<?php

declare(strict_types=1);

namespace App\Core\Media;

use App\Core\Upload\UploadResult;
use App\Core\Upload\UploadService;

final class MediaService
{
    private const AVATAR_ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    private const COVER_ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    private const KYC_ALLOWED_MIME_TYPES = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    private const AVATAR_MAX_SIZE = 2 * 1024 * 1024;
    private const COVER_MAX_SIZE = 5 * 1024 * 1024;
    private const KYC_MAX_SIZE = 10 * 1024 * 1024;

    public function __construct(
        private readonly UploadService $uploadService = new UploadService()
    ) {
    }

    public function uploadAvatar(array $uploadedFile, string $module, string $scope = 'global'): UploadResult
    {
        return $this->uploadService->upload(
            $uploadedFile,
            $module,
            'avatar',
            self::AVATAR_ALLOWED_MIME_TYPES,
            self::AVATAR_MAX_SIZE,
            [
                'visibility' => 'public',
                'scope' => $scope,
            ]
        );
    }

    public function uploadCover(array $uploadedFile, string $module, string $scope = 'global'): UploadResult
    {
        return $this->uploadService->upload(
            $uploadedFile,
            $module,
            'cover',
            self::COVER_ALLOWED_MIME_TYPES,
            self::COVER_MAX_SIZE,
            [
                'visibility' => 'public',
                'scope' => $scope,
            ]
        );
    }

    public function uploadKyc(array $uploadedFile, string $module, string $scope = 'global'): UploadResult
    {
        return $this->uploadService->upload(
            $uploadedFile,
            $module,
            'kyc',
            self::KYC_ALLOWED_MIME_TYPES,
            self::KYC_MAX_SIZE,
            [
                'visibility' => 'private',
                'scope' => $scope,
            ]
        );
    }

    public function deleteMedia(string $storageKey): void
    {
        $this->uploadService->delete($storageKey);
    }
}
