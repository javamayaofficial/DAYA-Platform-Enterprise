<?php

declare(strict_types=1);

namespace App\Core\Upload;

use RuntimeException;

final class UploadService
{
    public function upload(
        array $uploadedFile,
        string $module,
        string $assetType,
        array $allowedMimeTypes,
        int $maxSize,
        array $options = []
    ): UploadResult {
        $this->assertUploadedFileArray($uploadedFile);
        $this->assertSuccessfulUpload((int) $uploadedFile['error']);

        $tmpName = (string) $uploadedFile['tmp_name'];
        $size = (int) ($uploadedFile['size'] ?? 0);
        $visibility = $this->normalizeVisibility((string) ($options['visibility'] ?? 'public'));
        $moduleSegment = $this->normalizeSegment($module, 'module');
        $assetTypeSegment = $this->normalizeSegment($assetType, 'asset type');
        $scopeSegment = $this->normalizeSegment((string) ($options['scope'] ?? 'global'), 'scope');

        if (!is_uploaded_file($tmpName)) {
            throw new RuntimeException('File upload tidak valid atau bukan hasil upload HTTP.');
        }

        $this->validateSize($size, $maxSize);
        $mimeType = $this->validateMime($tmpName, $allowedMimeTypes);
        $filename = $this->generateFilename(
            (string) ($uploadedFile['name'] ?? ''),
            $this->extensionFromMimeType($mimeType)
        );

        $storageKey = $this->buildStorageKey($visibility, $moduleSegment, $assetTypeSegment, $scopeSegment, $filename);
        $storagePath = $this->resolveStoragePath($storageKey);
        $directory = dirname($storagePath);

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('Direktori upload tidak dapat dibuat.');
        }

        if (!move_uploaded_file($tmpName, $storagePath)) {
            throw new RuntimeException('File upload gagal dipindahkan ke storage.');
        }

        return new UploadResult(
            $storageKey,
            $storagePath,
            $this->generatePublicUrl($storageKey),
            $visibility,
            $moduleSegment,
            $assetTypeSegment,
            $filename,
            (string) ($uploadedFile['name'] ?? ''),
            $mimeType,
            $size
        );
    }

    public function delete(string $storageKey): void
    {
        $normalizedKey = $this->normalizeStorageKey($storageKey);
        $path = $this->resolveStoragePath($normalizedKey);

        if (!is_file($path)) {
            return;
        }

        if (!unlink($path)) {
            throw new RuntimeException('File upload gagal dihapus.');
        }

        $this->cleanupEmptyDirectories(dirname($path));
    }

    public function validateMime(string $filePath, array $allowedMimeTypes): string
    {
        if (!is_file($filePath)) {
            throw new RuntimeException('File untuk validasi MIME tidak ditemukan.');
        }

        if ($allowedMimeTypes === []) {
            throw new RuntimeException('Allowed MIME types wajib diisi.');
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            throw new RuntimeException('Ekstensi fileinfo tidak tersedia.');
        }

        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        if (!is_string($mimeType) || trim($mimeType) === '') {
            throw new RuntimeException('MIME type file tidak dapat dideteksi.');
        }

        $normalizedMimeType = strtolower(trim($mimeType));
        $normalizedAllowedMimeTypes = array_map(
            static fn (string $item): string => strtolower(trim($item)),
            $allowedMimeTypes
        );

        if (!in_array($normalizedMimeType, $normalizedAllowedMimeTypes, true)) {
            throw new RuntimeException('MIME type file tidak diizinkan: ' . $normalizedMimeType);
        }

        return $normalizedMimeType;
    }

    public function validateSize(int $size, int $maxSize): void
    {
        if ($maxSize <= 0) {
            throw new RuntimeException('Maximum size wajib lebih besar dari 0.');
        }

        if ($size < 0) {
            throw new RuntimeException('Ukuran file tidak valid.');
        }

        if ($size > $maxSize) {
            throw new RuntimeException('Ukuran file melebihi batas maksimum.');
        }
    }

    public function generateFilename(string $originalName, ?string $preferredExtension = null): string
    {
        $extension = $preferredExtension !== null && trim($preferredExtension) !== ''
            ? $this->normalizeExtension($preferredExtension)
            : $this->normalizeExtension((string) pathinfo($originalName, PATHINFO_EXTENSION));

        $random = bin2hex(random_bytes(16));

        return $extension === '' ? $random : $random . '.' . $extension;
    }

    public function resolveStoragePath(string $storageKey = ''): string
    {
        $normalizedKey = $this->normalizeStorageKey($storageKey);

        return $normalizedKey === ''
            ? storage_path('uploads')
            : storage_path('uploads/' . str_replace('/', DIRECTORY_SEPARATOR, $normalizedKey));
    }

    public function generatePublicUrl(string $storageKey): string
    {
        $normalizedKey = $this->normalizeStorageKey($storageKey);
        $mediaPath = '/media/' . $normalizedKey;
        $configuredUrl = trim((string) config('app.url', ''));

        if ($configuredUrl === '') {
            return app_url($mediaPath);
        }

        $parsedUrl = parse_url($configuredUrl);
        $scheme = $parsedUrl['scheme'] ?? '';
        $host = $parsedUrl['host'] ?? '';
        if (!is_string($scheme) || !is_string($host) || $scheme === '' || $host === '') {
            return app_url($mediaPath);
        }

        $authority = $host;
        if (isset($parsedUrl['port'])) {
            $authority .= ':' . (int) $parsedUrl['port'];
        }

        $basePath = trim((string) ($parsedUrl['path'] ?? ''), '/');
        $resolvedPath = ($basePath !== '' ? '/' . $basePath : '') . $mediaPath;

        return $scheme . '://' . $authority . $resolvedPath;
    }

    private function buildStorageKey(
        string $visibility,
        string $module,
        string $assetType,
        string $scope,
        string $filename
    ): string {
        return implode('/', [
            $visibility,
            $module,
            $assetType,
            date('Y'),
            date('m'),
            $scope,
            $filename,
        ]);
    }

    private function assertUploadedFileArray(array $uploadedFile): void
    {
        foreach (['name', 'tmp_name', 'error', 'size'] as $key) {
            if (!array_key_exists($key, $uploadedFile)) {
                throw new RuntimeException('Struktur uploaded file tidak valid. Key `' . $key . '` tidak ditemukan.');
            }
        }
    }

    private function assertSuccessfulUpload(int $errorCode): void
    {
        if ($errorCode === UPLOAD_ERR_OK) {
            return;
        }

        $messages = [
            UPLOAD_ERR_INI_SIZE => 'Ukuran file melampaui batas upload_max_filesize.',
            UPLOAD_ERR_FORM_SIZE => 'Ukuran file melampaui batas form.',
            UPLOAD_ERR_PARTIAL => 'File hanya ter-upload sebagian.',
            UPLOAD_ERR_NO_FILE => 'Tidak ada file yang di-upload.',
            UPLOAD_ERR_NO_TMP_DIR => 'Direktori temporary upload tidak tersedia.',
            UPLOAD_ERR_CANT_WRITE => 'File upload gagal ditulis ke disk.',
            UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP.',
        ];

        throw new RuntimeException($messages[$errorCode] ?? 'Terjadi kesalahan upload yang tidak dikenal.');
    }

    private function normalizeVisibility(string $visibility): string
    {
        $normalized = strtolower(trim($visibility));
        if (!in_array($normalized, ['public', 'private'], true)) {
            throw new RuntimeException('Visibility upload tidak valid.');
        }

        return $normalized;
    }

    private function normalizeSegment(string $value, string $label): string
    {
        $normalized = strtolower(trim($value));
        $normalized = preg_replace('~[^a-z0-9\-_\/]+~', '-', $normalized);
        $normalized = is_string($normalized) ? trim($normalized, '-_/') : '';
        $normalized = str_replace('\\', '/', $normalized);
        $normalized = preg_replace('#/+#', '/', $normalized);

        if (!is_string($normalized) || $normalized === '' || str_contains($normalized, '..')) {
            throw new RuntimeException('Segment upload untuk ' . $label . ' tidak valid.');
        }

        return $normalized;
    }

    private function normalizeStorageKey(string $storageKey): string
    {
        $normalizedKey = trim(str_replace('\\', '/', $storageKey), '/');
        if ($normalizedKey === '') {
            return '';
        }

        if (str_contains($normalizedKey, '..')) {
            throw new RuntimeException('Storage key upload tidak valid.');
        }

        return $normalizedKey;
    }

    private function normalizeExtension(string $extension): string
    {
        $normalized = strtolower(trim($extension, ". \t\n\r\0\x0B"));
        if ($normalized === '') {
            return '';
        }

        $normalized = preg_replace('/[^a-z0-9]+/', '', $normalized);

        return is_string($normalized) ? $normalized : '';
    }

    private function extensionFromMimeType(string $mimeType): string
    {
        return match (strtolower(trim($mimeType))) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'application/pdf' => 'pdf',
            'audio/mpeg' => 'mp3',
            'audio/mp4', 'audio/x-m4a' => 'm4a',
            default => '',
        };
    }

    private function cleanupEmptyDirectories(string $directory): void
    {
        $uploadsRoot = $this->resolveStoragePath();
        $currentDirectory = $directory;

        while ($currentDirectory !== $uploadsRoot && str_starts_with($currentDirectory, $uploadsRoot . DIRECTORY_SEPARATOR)) {
            $items = scandir($currentDirectory);
            if (!is_array($items)) {
                break;
            }

            $items = array_values(array_diff($items, ['.', '..']));
            if ($items !== []) {
                break;
            }

            if (!rmdir($currentDirectory)) {
                break;
            }

            $currentDirectory = dirname($currentDirectory);
        }
    }
}
