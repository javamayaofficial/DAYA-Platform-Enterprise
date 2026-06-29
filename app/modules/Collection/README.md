# COLLECTION MODULE

## Metadata
- Modul: `Collection`
- Bounded Context: `BC-COL`
- Status: `IMPLEMENTED FOR FOUNDATION`
- Scope: container Content milik Creator, pengurutan item, publish/draft, visibility, halaman publik, monitoring admin, dan soft delete.

## Tujuan
- Menyediakan wadah terstruktur untuk mengelompokkan banyak `Content`.
- Menjaga relasi resmi `Creator -> Collection -> CollectionItem -> Content`.
- Memastikan `Collection` tidak menyimpan isi karya, hanya referensi terurut ke `Content`.

## Struktur Implementasi
- `controllers/`: creator self-service, public directory, dan admin monitoring.
- `models/`: entity `Collection`, `CollectionItem`, dan repository persistence.
- `services/`: orchestration CRUD, publish/draft, item management, dan public lookup.
- `policies/`: authorization owner/admin/public.
- `resources/`: transform detail, list, item, dan statistik.
- `views/`: dashboard, form create/edit, detail owner, directory admin, dan halaman publik.
- `routes.php`: route creator, public, dan admin.

## Dependensi
- Fondasi framework: `BaseModule`, `BaseController`, `BaseService`, `BaseRepository`, `BaseModel`, `BaseRequest`, `BaseResponse`.
- Modul `Creator` sebagai owner relation `Creator -> many Collection`.
- Modul `Content` sebagai referensi item melalui `content_id`.
- Modul `Authentication` untuk middleware auth, CSRF, dan permission admin monitoring.
- Migration: `database/migrations/20260629_000005_create_collection_module.sql`.
- Seeder permission: `database/seeders/20260629_000003_seed_collection_permissions.sql`.

## Dokumen Modul
- `BUSINESS_RULES.md`
- `FLOW.md`
- `DATABASE.md`
- `API.md`
- `UI.md`
- `DEVELOPMENT_NOTES.md`
- `TESTING_CHECKLIST.md`
- `DEPLOYMENT_NOTES.md`
