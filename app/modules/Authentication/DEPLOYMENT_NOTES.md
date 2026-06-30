# DEPLOYMENT NOTES

## Urutan Deploy
- Jalankan Phase 1 bootstrap terlebih dahulu.
- Import `database/migrations/20260628_000001_create_authentication_module.sql`.
- Pastikan `storage/config/.env` sudah terisi dan koneksi DB aktif.
- Jika instalasi dilakukan lewat `/install`, verifikasi route tersebut otomatis terkunci setelah `APP_INSTALLED=1`.

## Konfigurasi
- `APP_URL` harus benar agar tautan verifikasi dan reset password valid.
- `AUTH_MAIL_MODE=log` cocok untuk local/shared hosting; `mail` bisa dipakai jika fungsi mail PHP aktif; `mailketing` dipakai untuk integrasi API Mailketing.
- `SESSION_SECURE=1` wajib di production HTTPS.
- Pastikan `storage/logs/` dapat ditulis karena auth mail selalu mencatat email keluar dan kegagalan delivery `mail()` ke `storage/logs/auth-mail.log`.
- Saat `AUTH_MAIL_MODE=mailketing`, isi `AUTH_MAILKETING_API_TOKEN`, `AUTH_MAILKETING_FROM_NAME`, dan `AUTH_MAILKETING_FROM_EMAIL`.
- Pastikan `AUTH_MAILKETING_FROM_EMAIL` sudah terdaftar sebagai sender/domain di Mailketing agar request tidak ditolak.

## Smoke Test
- Buka `/auth/register`, `/auth/login`, `/auth/forgot-password`.
- Cek `storage/logs/auth-mail.log` setelah register/reset password.
- Jika `AUTH_MAIL_MODE=mail`, pastikan tidak muncul baris `MAIL_DELIVERY_FAILED`.
- Jika `AUTH_MAIL_MODE=mailketing`, pastikan muncul baris `MAILKETING_SENT` dan tidak ada `MAILKETING_FAILED`.
- Login sebagai user aktif dan buka `/auth/security`.
- Uji admin RBAC setelah memberi role `super_admin` atau `admin_yayasan`.
