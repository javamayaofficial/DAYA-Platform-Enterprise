# DEPLOYMENT NOTES

## Urutan Deploy
1. Jalankan migration Authentication bila belum ada.
2. Jalankan seluruh migration Creator.
3. Jalankan migration Content.
4. Jalankan `database/migrations/20260629_000005_create_collection_module.sql`.
5. Jalankan `database/seeders/20260629_000003_seed_collection_permissions.sql`.
6. Pastikan folder `app/modules/Collection` ikut ter-deploy penuh.

## Validasi Pasca Deploy
- Creator yang sudah memiliki profil Creator dapat membuka `/collection`.
- Admin dengan role `super_admin` atau `admin_yayasan` dapat membuka `/collection/admin`.
- Route publik `/collections` hanya menampilkan collection berstatus `published` dan `visibility = public`.
