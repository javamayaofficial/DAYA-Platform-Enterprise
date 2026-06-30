<?php

declare(strict_types=1);

namespace App\Modules\Story\Models;

use App\Core\Modular\BaseModel;

final class Story extends BaseModel
{
    public function __construct(
        public int $id,
        public int $creatorId,
        public ?int $collectionId,
        public string $storyCode,
        public string $title,
        public string $subtitle,
        public string $slug,
        public string $summary,
        public string $body,
        public string $cover,
        public string $language,
        public string $genre,
        public string $tags,
        public int $wordCount,
        public int $readingTime,
        public string $status,
        public string $visibility,
        public string $seoTitle,
        public string $seoDescription,
        public string $canonicalUrl,
        public string $ogTitle,
        public string $ogDescription,
        public string $ogImage,
        public string $jsonLdPlaceholder,
        public ?string $publishedAt,
        public ?string $deletedAt,
        public string $createdAt,
        public string $updatedAt,
        public ?string $creatorDisplayName = null,
        public ?string $creatorSlug = null,
        public ?string $creatorCode = null,
        public ?string $collectionTitle = null,
        public ?string $collectionSlug = null,
        public ?string $collectionCode = null
    ) {
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) self::value($row, 'id', 0),
            (int) self::value($row, 'creator_id', 0),
            self::value($row, 'collection_id') !== null ? (int) self::value($row, 'collection_id') : null,
            (string) self::value($row, 'story_code', ''),
            (string) self::value($row, 'title', ''),
            (string) self::value($row, 'subtitle', ''),
            (string) self::value($row, 'slug', ''),
            (string) self::value($row, 'summary', ''),
            (string) self::value($row, 'body', ''),
            (string) self::value($row, 'cover', ''),
            (string) self::value($row, 'language', 'id'),
            (string) self::value($row, 'genre', ''),
            (string) self::value($row, 'tags', ''),
            (int) self::value($row, 'word_count', 0),
            (int) self::value($row, 'reading_time', 0),
            (string) self::value($row, 'status', 'draft'),
            (string) self::value($row, 'visibility', 'private'),
            (string) self::value($row, 'seo_title', ''),
            (string) self::value($row, 'seo_description', ''),
            (string) self::value($row, 'canonical_url', ''),
            (string) self::value($row, 'og_title', ''),
            (string) self::value($row, 'og_description', ''),
            (string) self::value($row, 'og_image', ''),
            (string) self::value($row, 'json_ld_placeholder', ''),
            self::value($row, 'published_at'),
            self::value($row, 'deleted_at'),
            (string) self::value($row, 'created_at', ''),
            (string) self::value($row, 'updated_at', ''),
            self::value($row, 'creator_display_name'),
            self::value($row, 'creator_slug'),
            self::value($row, 'creator_code'),
            self::value($row, 'collection_title'),
            self::value($row, 'collection_slug'),
            self::value($row, 'collection_code')
        );
    }

    public function publicUrl(): string
    {
        return '/stories/' . $this->slug;
    }
}
