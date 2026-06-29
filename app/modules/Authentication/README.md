# AUTHENTICATION MODULE

## Metadata
- Modul: `Authentication`
- Bounded Context: `BC-IAM`
- Status: `IMPLEMENTED FOR PHASE 2`
- Scope: register, login, logout, forgot/reset password, email verification, remember me, session management, device sessions, login history, multi-role, permission, RBAC.

## Tujuan
- Menjadi fondasi identitas dan kontrol akses seluruh platform.
- Menegakkan `password_hash()`, CSRF, login rate limit, secure session, dan least-privilege RBAC.
- Menyediakan area admin untuk role assignment dan role-permission matrix.

## Struktur Implementasi
- `controllers/`: entry point HTTP untuk auth, security, dan admin RBAC.
- `models/`: repository dan model entity auth.
- `services/`: orchestration register/login/logout/remember/verification/reset/RBAC.
- `middleware/`: guest guard dan permission guard.
- `views/`: layar auth, security dashboard, dan admin RBAC.
- `routes.php`: registrasi seluruh route modul.

## Dependensi
- Fondasi bootstrap Phase 1: router, session, CSRF middleware, config, database, logger.
- Database MySQL dengan migrasi `database/migrations/20260628_000001_create_authentication_module.sql`.

## Dokumen Modul
- `BUSINESS_RULES.md`
- `FLOW.md`
- `DATABASE.md`
- `API.md`
- `UI.md`
- `DEVELOPMENT_NOTES.md`
- `TESTING_CHECKLIST.md`
- `DEPLOYMENT_NOTES.md`
