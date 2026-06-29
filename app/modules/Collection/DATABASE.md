# DATABASE

## Tabel Utama
- `collections`: aggregate root container milik Creator.
- `collection_items`: relasi terurut `Collection -> Content`.

## Relasi
- `collections.creator_id -> creator_profiles.id`
- `collection_items.collection_id -> collections.id`
- `collection_items.content_id -> contents.id`

## Atribut Penting `collections`
- Identity: `collection_code`, `title`, `slug`
- Presentation: `description`, `cover_image_url`
- Control: `visibility`, `status`, `published_at`
- Audit: `deleted_at`, `created_at`, `updated_at`

## Atribut Penting `collection_items`
- Reference: `collection_id`, `content_id`
- Ordering: `sort_order`
- Audit: `deleted_at`, `created_at`, `updated_at`

## Index Penting
- Unique `collection_code`
- Unique `slug`
- Index `creator_id`, `status`, `visibility`, `deleted_at`
- Index `collection_id`, `content_id`, `sort_order`, `deleted_at`

## Setup
- Jalankan migration: `database/migrations/20260629_000005_create_collection_module.sql`
- Jalankan seeder: `database/seeders/20260629_000003_seed_collection_permissions.sql`
