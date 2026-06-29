# DEPLOYMENT NOTES

## Urutan Deploy
1. Jalankan migration Authentication bila belum ada.
2. Jalankan `database/migrations/20260629_000002_create_creator_module.sql`.
3. Jalankan `database/migrations/20260629_000003_extend_creator_identity.sql`.
4. Jalankan `database/seeders/20260629_000001_seed_creator_permissions.sql`.
5. Pastikan folder module `app/modules/Creator` ikut ter-deploy penuh.

## Validasi Pasca Deploy
- Admin dengan role `super_admin` atau `admin_yayasan` bisa membuka `/creator/admin`.
- User biasa bisa mendaftar Creator melalui `/creator/register`.
- Halaman publik `/creators` hanya menampilkan Creator aktif.
