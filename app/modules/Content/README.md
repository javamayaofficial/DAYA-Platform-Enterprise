# CONTENT MODULE

## Metadata
- Modul: `Content`
- Bounded Context: `BC-CNT`
- Status: `IMPLEMENTED FOR FOUNDATION`
- Scope: agregat karya utama, content type, metadata publikasi, moderation workflow, SEO, access policy, content part, statistik snapshot, public page, search, pagination, dan soft delete.

## Tujuan
- Menjadikan `Content` sebagai entitas utama seluruh karya DAYA Platform.
- Menyatukan seluruh karya seperti story, novel, artikel, audio, podcast, dan ebook ke dalam satu model `content_type`.
- Menyediakan fondasi yang akan direferensikan modul lain melalui `content_id`.

## Struktur Implementasi
- `controllers/`: creator self-service, public directory, dan admin moderation.
- `models/`: entity `Content`, `ContentPart`, dan repository persistence.
- `services/`: orchestration CRUD, moderasi, search, dan public lookup.
- `policies/`: authorization owner/admin/public.
- `resources/`: transform detail, list, part, dan statistik.
- `views/`: dashboard, form create/edit, detail owner, directory admin, directory publik, dan partials.
- `routes.php`: route creator, public, dan admin.

## Dependensi
- Fondasi framework: `BaseModule`, `BaseController`, `BaseService`, `BaseRepository`, `BaseModel`, `BaseRequest`, `BaseResponse`.
- Modul `Creator` sebagai owner relation `Creator -> many Content`.
- Modul `Authentication` untuk middleware auth, CSRF, dan permission admin moderation.
- Architecture decision resmi: `docs/CONTENT_ARCHITECTURE_DECISION.md`.
- Migration: `database/migrations/20260629_000004_create_content_module.sql`.
- Seeder permission: `database/seeders/20260629_000002_seed_content_permissions.sql`.

## Dokumen Modul
- `BUSINESS_RULES.md`
- `FLOW.md`
- `DATABASE.md`
- `API.md`
- `UI.md`
- `DEVELOPMENT_NOTES.md`
- `TESTING_CHECKLIST.md`
- `DEPLOYMENT_NOTES.md`
