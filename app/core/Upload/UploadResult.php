<?php

declare(strict_types=1);

namespace App\Core\Upload;

final readonly class UploadResult
{
    public function __construct(
        public string $storageKey,
        public string $storagePath,
        public string $publicUrl,
        public string $visibility,
        public string $module,
        public string $assetType,
        public string $filename,
        public string $originalName,
        public string $mimeType,
        public int $size
    ) {
    }

    public function toArray(): array
    {
        return [
            'storage_key' => $this->storageKey,
            'storage_path' => $this->storagePath,
            'public_url' => $this->publicUrl,
            'visibility' => $this->visibility,
            'module' => $this->module,
            'asset_type' => $this->assetType,
            'filename' => $this->filename,
            'original_name' => $this->originalName,
            'mime_type' => $this->mimeType,
            'size' => $this->size,
        ];
    }
}
