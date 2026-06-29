# FLOW

## Registrasi Creator
1. User login membuka `/creator/register`.
2. User mengirim identity, profile enrichment, multi category, multi skill, data KYC, dan pengaturan publik.
3. Service membuat `creator_profiles`, `creator_applications`, `creator_statistics`, kategori, dan skill.
4. Repository menghasilkan `creator_code` dan menyimpan `slug` publik.
5. Repository menambahkan role `creator` ke user bila belum ada.
6. Status awal Creator menjadi `pending_review`.

## Review Admin
1. Admin membuka `/creator/admin` dan memilih Creator.
2. Admin meninjau identity, KYC, social links, portfolio, achievement, dan statistik snapshot.
3. Admin mengirim review, verification status, creator level, creator rank, dan badge ke `/creator/admin/{id}/review`.
4. Status Creator, badge, statistik snapshot, dan aplikasi terakhir diperbarui serentak.

## Public Page
1. Visitor membuka `/creators`.
2. Search hanya mengambil Creator `active` dan `public_page_enabled`.
3. URL publik utama menggunakan `slug`.
4. Saat detail publik dibuka, `profile_view_count` bertambah.
