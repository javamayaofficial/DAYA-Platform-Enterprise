# DATABASE

## Tabel Utama
- `users`: identitas, hash password, status akun, verifikasi email, last login.
- `roles`: daftar role minimum platform.
- `permissions`: daftar permission granular.
- `user_roles`: pivot multi-role user.
- `role_permissions`: pivot role ke permission.
- `email_verification_tokens`: token verifikasi email.
- `password_reset_tokens`: token reset password.
- `remember_tokens`: token remember me.
- `device_sessions`: sesi perangkat aktif.
- `login_history`: riwayat login sukses/gagal/blocked.

## Prinsip
- Semua akses data memakai prepared statements.
- Token yang disimpan di DB adalah hash, bukan raw token.
- FK memakai `ON DELETE CASCADE` atau `SET NULL` sesuai kebutuhan audit.
- Seluruh tabel memakai `utf8mb4` dan `InnoDB`.

## Migrasi
- Lihat `database/migrations/20260628_000001_create_authentication_module.sql`.
