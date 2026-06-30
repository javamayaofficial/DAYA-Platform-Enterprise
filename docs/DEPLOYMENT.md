# DEPLOYMENT

## Tujuan
- Dokumen ini menjadi checklist deploy minimum untuk DAYA Platform pada shared hosting / cPanel / FastPanel.
- Fokusnya adalah baseline yang aman untuk modul aktif saat ini: `Authentication`, `Creator`, `Content`, `Collection`, dan `Story`.

## Prasyarat
- PHP 8.x aktif.
- MySQL 8.x aktif.
- Web root diarahkan ke folder `public/`.
- Folder `storage/` dapat ditulis oleh web server.
- File environment berada di `storage/config/.env`.
- Folder `storage/config`, `storage/logs`, dan `storage/uploads` harus tetap berada di luar web root publik dan sekarang sudah memiliki `.htaccess` deny sebagai lapisan tambahan.

## Konfigurasi Wajib Production
- `APP_ENV=production`
- `APP_DEBUG=0`
- `APP_URL=https://your-domain.com`
- `APP_LOG_LEVEL=info`
- `APP_INSTALLED=1`
- `SESSION_SECURE=1`
- `SESSION_HTTP_ONLY=1`
- `SESSION_SAME_SITE=Lax`
- `SECURITY_HEADERS_ENABLED=1`
- `SECURITY_HSTS_ENABLED=0` sebelum HTTPS benar-benar final, lalu ubah ke `1` setelah validasi penuh
- Untuk mail production, gunakan `AUTH_MAIL_MODE=mailketing` dan isi `AUTH_MAILKETING_API_TOKEN`, `AUTH_MAILKETING_FROM_NAME`, serta `AUTH_MAILKETING_FROM_EMAIL`
- Untuk WhatsApp notification production, gunakan `WHATSAPP_MODE=fonnte`, isi `WHATSAPP_FONNTE_TOKEN`, tentukan `WHATSAPP_ADMIN_TARGETS`, lalu atur toggle event `WHATSAPP_EVENT_ADMIN_CREATOR_REGISTRATION` dan `WHATSAPP_EVENT_ADMIN_CREATOR_REVIEW` sesuai kebutuhan

## Urutan Deploy
1. Upload source code.
2. Pastikan hanya folder `public/` yang diakses web.
3. Siapkan `.env` di `storage/config/.env`.
4. Jika memakai installer, verifikasi installer dapat menulis ke `storage/config/.env` dan setelah instalasi selesai route `/install` berubah menjadi `403`.
5. Import migration berurutan:
   - `20260628_000001_create_authentication_module.sql`
   - `20260629_000002_create_creator_module.sql`
   - `20260629_000003_extend_creator_identity.sql`
   - `20260629_000004_create_content_module.sql`
   - `20260629_000005_create_collection_module.sql`
   - `20260630_000001_create_story_module.sql`
6. Jalankan seeder permission berurutan:
   - `20260629_000001_seed_creator_permissions.sql`
   - `20260629_000002_seed_content_permissions.sql`
   - `20260629_000003_seed_collection_permissions.sql`
   - `20260630_000001_seed_story_permissions.sql`
7. Verifikasi route `/health` dalam mode production hanya menampilkan status ringkas.

## Smoke Test Minimum
- Buka `/auth/register`, `/auth/login`, `/auth/forgot-password`.
- Register user baru lalu verifikasi email dari log mail.
- Jika `AUTH_MAIL_MODE=mail`, cek `storage/logs/auth-mail.log` untuk memastikan tidak ada entri `MAIL_DELIVERY_FAILED`.
- Jika `AUTH_MAIL_MODE=mailketing`, cek `storage/logs/auth-mail.log` untuk memastikan muncul `MAILKETING_SENT` dan tidak ada `MAILKETING_FAILED`.
- Jika `WHATSAPP_MODE=fonnte`, siapkan satu nomor uji dan pastikan `storage/logs/whatsapp.log` mencatat `FONNTE_SENT` tanpa `FONNTE_FAILED`.
- Ajukan Creator baru dan lakukan review admin untuk memastikan flow WA internal admin ikut tercatat.
- Login user aktif dan buka `/auth/security`.
- Buat `Creator`, `Content`, `Collection`, dan `Story`.
- Cek halaman publik:
  - `/creators/{slug}`
  - `/contents/{slug}` bila ada
  - `/collections/{slug}`
  - `/stories/{slug}`
- Pastikan create/edit/delete/publish utama berjalan.

## Checklist Keamanan Sebelum Go Live
- Pastikan route mock bootstrap tidak aktif di production.
- Pastikan `/health` tidak mengekspos `debug`, `installed`, dan detail DB.
- Pastikan `/install` mengembalikan `403` setelah aplikasi terpasang.
- Pastikan `APP_DEBUG=0`.
- Pastikan `SESSION_SECURE=1` pada HTTPS.
- Pastikan cookie remember/login memakai `httponly`.
- Pastikan security headers aktif.
- Pastikan `.env` tidak berada di web root.
- Pastikan `storage/config/`, `storage/logs/`, dan `storage/uploads/` tidak dapat diakses publik.
- Pastikan `storage/logs/auth-mail.log` dapat ditulis agar proses verifikasi/reset tidak gagal saat mode `log` maupun saat pencatatan kegagalan `mail()`.
- Pastikan `storage/logs/whatsapp.log` dapat ditulis agar kegagalan/pengiriman WA dari Fonnte dapat diaudit.
- Pastikan sender `AUTH_MAILKETING_FROM_EMAIL` sudah terverifikasi/terdaftar di Mailketing sebelum go-live.
- Pastikan device Fonnte sudah terkoneksi dan token tidak diekspos ke frontend.
- Pastikan `WHATSAPP_ADMIN_TARGETS` berisi nomor admin yang valid bila notifikasi internal ingin aktif.
- Pastikan nomor admin di `WHATSAPP_ADMIN_TARGETS` menggunakan format nomor yang bisa dinormalisasi, misalnya `0812...` atau `+62812...`.
- Pastikan toggle event WA admin sudah sesuai skenario go-live agar event yang belum siap tidak ikut terkirim.

## Catatan Blocker Yang Masih Ada
- Upload handling aman belum ada.
- Testing otomatis belum tersedia.
- Audit logging lintas modul belum lengkap.

## Operasional Setelah Deploy
- Simpan backup `.env` dan database sebelum perubahan besar.
- Pantau `storage/logs/` setelah deploy pertama untuk mendeteksi warning/error runtime.
- Pantau `storage/logs/auth-mail.log` setelah uji register/reset password pertama untuk memastikan alur email dan fallback logging berjalan normal.
- Pantau `storage/logs/whatsapp.log` setelah uji WhatsApp pertama untuk memastikan koneksi device Fonnte dan token valid.
- Jalankan smoke test ulang setiap selesai import migration/seeder baru.
- Jangan aktifkan `SECURITY_HSTS_ENABLED=1` sebelum HTTPS, redirect, dan sertifikat benar-benar tervalidasi.
