<?php

declare(strict_types=1);

namespace App\Modules\Story\Resources;

use App\Modules\Story\Models\Story;

final class StoryResource
{
    public static function detail(Story $story): array
    {
        return [
            'id' => $story->id,
            'creator_id' => $story->creatorId,
            'collection_id' => $story->collectionId,
            'story_code' => $story->storyCode,
            'title' => $story->title,
            'subtitle' => $story->subtitle,
            'slug' => $story->slug,
            'public_url' => $story->publicUrl(),
            'summary' => $story->summary,
            'body' => $story->body,
            'cover' => $story->cover,
            'language' => $story->language,
            'genre' => $story->genre,
            'tags' => self::tags($story->tags),
            'tags_string' => $story->tags,
            'reading_time' => $story->readingTime,
            'word_count' => $story->wordCount,
            'status' => $story->status,
            'visibility' => $story->visibility,
            'seo_title' => $story->seoTitle,
            'seo_description' => $story->seoDescription,
            'canonical_url' => $story->canonicalUrl,
            'og_title' => $story->ogTitle,
            'og_description' => $story->ogDescription,
            'og_image' => $story->ogImage,
            'json_ld_placeholder' => $story->jsonLdPlaceholder,
            'published_at' => $story->publishedAt,
            'deleted_at' => $story->deletedAt,
            'created_at' => $story->createdAt,
            'updated_at' => $story->updatedAt,
            'creator_display_name' => $story->creatorDisplayName,
            'creator_slug' => $story->creatorSlug,
            'creator_code' => $story->creatorCode,
            'collection_title' => $story->collectionTitle,
            'collection_slug' => $story->collectionSlug,
            'collection_code' => $story->collectionCode,
        ];
    }

    public static function listItem(Story $story): array
    {
        return [
            'id' => $story->id,
            'collection_id' => $story->collectionId,
            'story_code' => $story->storyCode,
            'title' => $story->title,
            'subtitle' => $story->subtitle,
            'slug' => $story->slug,
            'public_url' => $story->publicUrl(),
            'summary' => $story->summary,
            'cover' => $story->cover,
            'language' => $story->language,
            'genre' => $story->genre,
            'tags' => self::tags($story->tags),
            'reading_time' => $story->readingTime,
            'word_count' => $story->wordCount,
            'status' => $story->status,
            'visibility' => $story->visibility,
            'published_at' => $story->publishedAt,
            'deleted_at' => $story->deletedAt,
            'creator_display_name' => $story->creatorDisplayName,
            'creator_slug' => $story->creatorSlug,
            'collection_title' => $story->collectionTitle,
            'collection_slug' => $story->collectionSlug,
        ];
    }

    public static function statistics(Story $story): array
    {
        return [
            'word_count' => $story->wordCount,
            'reading_time' => $story->readingTime,
            'has_collection' => $story->collectionId !== null,
            'is_published' => $story->status === 'published',
            'is_scheduled' => $story->status === 'scheduled',
        ];
    }

    private static function tags(string $tags): array
    {
        $items = array_map('trim', explode(',', $tags));
        $items = array_filter($items, static fn (string $item): bool => $item !== '');

        return array_values(array_unique($items));
    }
}
