# BUSINESS RULES

## Identitas Creator
- Satu user maksimal memiliki satu profil Creator.
- `handle` Creator harus unik secara global dan tetap unik walau data sudah soft delete.
- `creator_code` dan `slug` Creator harus unik secara global.
- Status lifecycle Creator: `draft`, `pending_review`, `active`, `rejected`, `suspended`, `revoked`.
- `verification_status` memisahkan status verifikasi identitas dari status lifecycle operasional Creator.
- `creator_level` dan `creator_rank` dikelola melalui workflow admin review.

## Approval & KYC
- Registrasi Creator menghasilkan status awal `pending_review`.
- KYC wajib menyertakan nama legal, tipe dokumen, nomor dokumen, dan catatan aplikasi.
- Hanya admin yang memiliki permission `creator.admin.review` yang boleh mengubah status review.
- Approval `active` mengubah `kyc_status` menjadi `verified`.

## Public Page
- Halaman publik hanya tersedia untuk Creator `active`, `public_page_enabled = true`, dan belum di-soft-delete.
- Email publik hanya tampil bila `allow_public_contact = true`.
- Portfolio publik hanya tampil bila `show_portfolio_publicly = true`.
- URL publik utama menggunakan `slug` agar SEO friendly.

## Statistik & Finansial
- Statistik Creator dapat menyimpan snapshot follower, reads, listens, downloads, sponsor, donation, affiliate, dan wallet summary.
- Seluruh angka finansial di Creator hanyalah snapshot/projection untuk identitas publik dan dashboard, bukan sumber kebenaran finansial.
- Sumber kebenaran finansial tetap harus berasal dari ledger pada modul keuangan saat modul tersebut dibangun.

## Soft Delete
- Soft delete menandai `deleted_at`, mematikan halaman publik, dan mengubah status menjadi `revoked`.
- Soft deleted Creator tetap dianggap sebagai profil historis user dan tidak membuka pendaftaran Creator baru untuk user yang sama.
