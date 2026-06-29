# DAYA Platform

Platform modular berbasis PHP Native 8.x untuk DAYA Platform.

## Status Implementasi
- Core Bootstrap selesai.
- Module Manager selesai.
- Authentication dan RBAC selesai.
- Creator module selesai sebagai identitas digital utama untuk registrasi, identity profile, slug publik, badge, achievement, typed portfolio, multi skill, admin verification, public page, search, pagination, dan soft delete.
- Architecture Decision `Content` telah dibekukan sebagai aggregate root seluruh karya.
- Content module foundation selesai dengan `Content` sebagai entitas utama dan `content_type` sebagai klasifikasi karya.
- Collection module foundation selesai sebagai container terurut untuk banyak Content melalui relasi `Collection` dan `CollectionItem`.

## Modul Aktif
- `Authentication`
- `Creator`
- `Content`
- `Collection`

## Jalur Penting
- Front controller: `public/index.php`
- Installer: `public/install/index.php` atau route `/install`
- Config environment: `storage/config/.env`
- Log aplikasi: `storage/logs/`
- Migration auth: `database/migrations/20260628_000001_create_authentication_module.sql`
- Migration creator: `database/migrations/20260629_000002_create_creator_module.sql`
- Migration creator identity extension: `database/migrations/20260629_000003_extend_creator_identity.sql`
- Migration content: `database/migrations/20260629_000004_create_content_module.sql`
- Migration collection: `database/migrations/20260629_000005_create_collection_module.sql`
- Seeder creator permissions: `database/seeders/20260629_000001_seed_creator_permissions.sql`
- Seeder content permissions: `database/seeders/20260629_000002_seed_content_permissions.sql`
- Seeder collection permissions: `database/seeders/20260629_000003_seed_collection_permissions.sql`

## Catatan
- Proyek ini sengaja tidak memakai framework berat.
- Hanya `public/` yang boleh diekspos sebagai web root.
- Module Creator mengikuti fondasi modular yang sudah dibekukan tanpa mengubah core, config, atau Authentication.
- Seluruh karya platform kini berporos pada `Content`; detail permanennya dibekukan di `docs/CONTENT_ARCHITECTURE_DECISION.md`.
- Collection hanya berfungsi sebagai container Content; modul ini tidak menyimpan isi karya dan tidak memperkenalkan Series, Universe, Playlist, Recommendation, Search, atau Analytics.
