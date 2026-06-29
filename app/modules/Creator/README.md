# CREATOR MODULE

## Metadata
- Modul: `Creator`
- Bounded Context: `BC-CREATOR`
- Status: `IMPLEMENTED FOR MVP`
- Scope: creator identity, profil publik, social links, typed portfolio, multi category, multi skill, achievement, badge, statistik snapshot, admin review, public page, search, pagination, dan soft delete.

## Tujuan
- Menyediakan bounded context Creator yang modular di atas fondasi framework v1.0.
- Mengelola satu profil Creator untuk satu user dengan lifecycle approval yang jelas.
- Menyediakan area self-service Creator, area admin review, dan halaman publik creator aktif.

## Struktur Implementasi
- `controllers/`: flow dashboard, profil, public page, dan admin review.
- `models/`: entity creator dan repository persistence.
- `services/`: orchestration registrasi, update, approval, search, dan statistik.
- `policies/`: authorization own-profile dan admin review.
- `resources/`: transform data untuk detail dan list.
- `views/`: layar dashboard, form, profil, admin, public, dan partial pagination/flash.
- `routes.php`: route self-service, public directory, dan admin directory.

## Dependensi
- Fondasi framework: `BaseModule`, `BaseController`, `BaseService`, `BaseRepository`, `BaseModel`, `BaseRequest`, `BaseResponse`.
- Module `Authentication` untuk `AuthMiddleware`, `PermissionMiddleware`, role `creator`, dan permission admin.
- Database migration `database/migrations/20260629_000002_create_creator_module.sql`.
- Database migration extension `database/migrations/20260629_000003_extend_creator_identity.sql`.
- Seeder permission `database/seeders/20260629_000001_seed_creator_permissions.sql`.

## Dokumen Modul
- `BUSINESS_RULES.md`
- `FLOW.md`
- `DATABASE.md`
- `API.md`
- `UI.md`
- `DEVELOPMENT_NOTES.md`
- `TESTING_CHECKLIST.md`
- `DEPLOYMENT_NOTES.md`
