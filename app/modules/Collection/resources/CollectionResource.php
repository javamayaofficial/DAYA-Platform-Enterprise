<?php

declare(strict_types=1);

namespace App\Modules\Collection\Resources;

use App\Modules\Collection\Models\Collection;
use App\Modules\Collection\Models\CollectionItem;

final class CollectionResource
{
    public static function detail(Collection $collection): array
    {
        return [
            'id' => $collection->id,
            'creator_id' => $collection->creatorId,
            'collection_code' => $collection->collectionCode,
            'title' => $collection->title,
            'slug' => $collection->slug,
            'public_url' => $collection->publicUrl(),
            'description' => $collection->description,
            'cover_image_url' => $collection->coverImageUrl,
            'visibility' => $collection->visibility,
            'status' => $collection->status,
            'published_at' => $collection->publishedAt,
            'deleted_at' => $collection->deletedAt,
            'created_at' => $collection->createdAt,
            'updated_at' => $collection->updatedAt,
            'creator_display_name' => $collection->creatorDisplayName,
            'creator_slug' => $collection->creatorSlug,
            'creator_code' => $collection->creatorCode,
            'items' => array_map([self::class, 'item'], $collection->items),
        ];
    }

    public static function listItem(Collection $collection): array
    {
        return [
            'id' => $collection->id,
            'collection_code' => $collection->collectionCode,
            'title' => $collection->title,
            'slug' => $collection->slug,
            'public_url' => $collection->publicUrl(),
            'description' => $collection->description,
            'cover_image_url' => $collection->coverImageUrl,
            'visibility' => $collection->visibility,
            'status' => $collection->status,
            'published_at' => $collection->publishedAt,
            'deleted_at' => $collection->deletedAt,
            'creator_display_name' => $collection->creatorDisplayName,
            'creator_slug' => $collection->creatorSlug,
            'items_count' => count($collection->items),
        ];
    }

    public static function item(CollectionItem $item): array
    {
        return [
            'id' => $item->id,
            'content_id' => $item->contentId,
            'sort_order' => $item->sortOrder,
            'content_code' => $item->contentCode,
            'content_title' => $item->contentTitle,
            'content_slug' => $item->contentSlug,
            'content_excerpt' => $item->contentExcerpt,
            'cover_image_url' => $item->coverImageUrl,
            'content_status' => $item->contentStatus,
            'content_visibility' => $item->contentVisibility,
            'public_url' => $item->contentSlug !== null ? '/contents/' . $item->contentSlug : null,
        ];
    }

    public static function statistics(Collection $collection): array
    {
        return [
            'items_count' => count($collection->items),
            'published_items_count' => count(array_filter(
                $collection->items,
                static fn (CollectionItem $item): bool => $item->contentStatus === 'published'
            )),
        ];
    }
}
