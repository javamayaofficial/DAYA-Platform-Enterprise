# DATABASE — Story Module

## Table
### `stories`
Kolom utama:
- Ownership: `creator_id`, `collection_id` (nullable)
- Identity: `story_code`, `slug`
- Content: `title`, `subtitle`, `summary`, `body`, `cover`
- Meta: `language`, `genre`, `tags`, `word_count`, `reading_time`
- Publish: `status`, `visibility`, `published_at`
- SEO: `seo_title`, `seo_description`, `canonical_url`, `og_*`, `json_ld_placeholder`
- Audit: `created_at`, `updated_at`, `deleted_at`

## Index
- `uq_stories_story_code`, `uq_stories_slug`
- filter indexes: `creator_id`, `collection_id`, `status`, `visibility`, `published_at`, `deleted_at`

## Foreign Key
- `creator_id` → `creator_profiles.id` (CASCADE)
- `collection_id` → `collections.id` (SET NULL)

