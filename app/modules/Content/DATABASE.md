# DATABASE

## Tabel Utama
- `contents`: aggregate root seluruh karya.
- `content_parts`: sub-unit terurut milik sebuah Content.

## Relasi
- `contents.creator_id -> creator_profiles.id`
- `contents.reviewed_by_user_id -> users.id`
- `content_parts.content_id -> contents.id`

## Atribut Penting `contents`
- Identity: `content_code`, `content_type`, `title`, `slug`
- SEO: `seo_title`, `seo_description`
- Access: `access_policy`, `price_minor`, `currency_code`, `visibility`
- Moderation: `status`, `reviewed_at`, `reviewed_by_user_id`, `review_notes`, `published_at`
- Metrics snapshot: `views_count`, `likes_count`, `comments_count`, `shares_count`, `sponsor_count`, `donation_count`, `affiliate_count`, `revenue_minor`, `recommendation_score`

## Index Penting
- Unique `content_code`
- Unique `slug`
- Index `creator_id`, `content_type`, `status`, `visibility`, `deleted_at`

## Setup
- Jalankan migration: `database/migrations/20260629_000004_create_content_module.sql`
- Jalankan seeder: `database/seeders/20260629_000002_seed_content_permissions.sql`
