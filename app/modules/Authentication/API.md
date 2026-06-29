# API / ROUTES

## Public Auth
- `GET /auth/register`
- `POST /auth/register`
- `GET /auth/login`
- `POST /auth/login`
- `POST /auth/logout`
- `GET /auth/forgot-password`
- `POST /auth/forgot-password`
- `GET /auth/reset-password`
- `POST /auth/reset-password`
- `GET /auth/verify`
- `GET /auth/verify-notice`
- `POST /auth/verify-notice`

## Security Area
- `GET /auth/security`
- `GET /auth/security/sessions`
- `POST /auth/security/sessions/revoke`
- `GET /auth/security/login-history`

## Admin RBAC
- `GET /auth/admin/users`
- `GET /auth/admin/users/{id}/roles`
- `POST /auth/admin/users/{id}/roles`
- `GET /auth/admin/roles`

## Aturan
- Seluruh POST dilindungi CSRF.
- Admin area wajib lolos `AuthMiddleware` + `PermissionMiddleware`.
