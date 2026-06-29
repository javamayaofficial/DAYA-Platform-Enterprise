# DEPLOYMENT NOTES

## Urutan Deploy
- Jalankan Phase 1 bootstrap terlebih dahulu.
- Import `database/migrations/20260628_000001_create_authentication_module.sql`.
- Pastikan `storage/config/.env` sudah terisi dan koneksi DB aktif.

## Konfigurasi
- `APP_URL` harus benar agar tautan verifikasi dan reset password valid.
- `AUTH_MAIL_MODE=log` cocok untuk local/shared hosting; `mail` bisa dipakai jika fungsi mail PHP aktif.
- `SESSION_SECURE=1` wajib di production HTTPS.

## Smoke Test
- Buka `/auth/register`, `/auth/login`, `/auth/forgot-password`.
- Cek `storage/logs/auth-mail.log` setelah register/reset password.
- Login sebagai user aktif dan buka `/auth/security`.
- Uji admin RBAC setelah memberi role `super_admin` atau `admin_yayasan`.
