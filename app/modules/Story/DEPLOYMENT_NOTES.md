# DEPLOYMENT NOTES — Story Module

## Urutan Deploy (Manual SQL)
1. Jalankan migration sebelumnya: Authentication, Creator, Collection.
2. Jalankan migration Story:
   - `database/migrations/20260630_000001_create_story_module.sql`
3. Jalankan seeder permission Story:
   - `database/seeders/20260630_000001_seed_story_permissions.sql`

## Catatan
- Pastikan charset/collation `utf8mb4_unicode_ci`.
- Tidak ada dependency external.

