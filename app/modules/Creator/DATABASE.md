# DATABASE

## Tabel Utama
- `creator_profiles`: profil utama Creator, status, setting publik, statistik, approval metadata, dan soft delete.
- `creator_applications`: snapshot data aplikasi/KYC dan hasil review terakhir.
- `creator_social_links`: daftar tautan sosial Creator.
- `creator_portfolios`: daftar karya/portfolio Creator.
- `creator_categories`: kategori tambahan untuk multi category.
- `creator_skills`: skill Creator.
- `creator_achievements`: achievement Creator.
- `creator_badges`: badge/label identitas Creator.
- `creator_statistics`: snapshot statistik publik dan dashboard Creator.

## Relasi
- `creator_profiles.user_id -> users.id`
- `creator_applications.creator_id -> creator_profiles.id`
- `creator_social_links.creator_id -> creator_profiles.id`
- `creator_portfolios.creator_id -> creator_profiles.id`
- `creator_categories.creator_id -> creator_profiles.id`
- `creator_skills.creator_id -> creator_profiles.id`
- `creator_achievements.creator_id -> creator_profiles.id`
- `creator_badges.creator_id -> creator_profiles.id`
- `creator_statistics.creator_id -> creator_profiles.id`

## Index Penting
- Unique `user_id` di `creator_profiles` untuk menegakkan satu user satu Creator.
- Unique `handle` di `creator_profiles` untuk public page.
- Unique `creator_code` dan `slug` di `creator_profiles` untuk identitas publik.
- Index `status`, `category`, dan `deleted_at` untuk list/search admin dan publik.

## Setup
- Jalankan migration: `database/migrations/20260629_000002_create_creator_module.sql`
- Jalankan migration extension: `database/migrations/20260629_000003_extend_creator_identity.sql`
- Jalankan seeder: `database/seeders/20260629_000001_seed_creator_permissions.sql`
