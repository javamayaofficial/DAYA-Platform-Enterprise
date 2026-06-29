# DEVELOPMENT NOTES

## Urutan Internal
- Fondasi data: users, roles, permissions, tokens, sessions, login history.
- Services inti: validator, register, verify, reset password, login/logout, RBAC.
- HTTP layer: controller, route, middleware permission/guest.
- Presentation: auth views, security views, admin RBAC views.

## Keputusan Teknis
- Remember me memakai pola selector + validator hash.
- Email verification dan reset password dikirim ke mail log agar shared-hosting friendly.
- Auth context disegarkan setiap request melalui `AuthBootstrapService`.
- Role default registrasi saat ini adalah `reader`.

## Catatan
- Modul ini belum membuat token API stateless karena scope Phase 2 fokus web/session auth.
