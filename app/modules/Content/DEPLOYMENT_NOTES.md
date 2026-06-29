# DEPLOYMENT NOTES

## Urutan Deploy
1. Jalankan migration Authentication bila belum ada.
2. Jalankan seluruh migration Creator.
3. Jalankan `database/migrations/20260629_000004_create_content_module.sql`.
4. Jalankan `database/seeders/20260629_000002_seed_content_permissions.sql`.
5. Pastikan folder `app/modules/Content` ikut ter-deploy penuh.

## Validasi Pasca Deploy
- Creator yang sudah memiliki profil Creator dapat membuka `/content`.
- Admin dengan role `super_admin` atau `admin_yayasan` dapat membuka `/content/admin`.
- Route publik `/contents` hanya menampilkan content berstatus `published`.
