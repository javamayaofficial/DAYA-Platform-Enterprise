# BUSINESS RULES

## Aggregate Root
- Semua karya berasal dari satu entitas utama `Content`.
- `Content Part` hanya boleh diakses melalui `Content`.

## Content Type
- `Story`, `Novel`, `Cerpen`, `Artikel`, `Audio`, `Podcast`, `Ebook`, dan jenis karya lain diperlakukan sebagai `content_type`.
- Tidak boleh ada modul karya utama terpisah per jenis.

## Ownership
- Setiap `Content` dimiliki tepat satu `Creator`.
- User harus memiliki profil Creator sebelum dapat membuat Content.

## Lifecycle
- Lifecycle utama: `draft -> in_review -> published -> rejected/archived/removed`.
- Hanya `published` yang boleh tampil ke publik.
- Content yang sudah dipublish dan diperbarui dapat berpindah ke `updated` sampai review atau publish ulang dilakukan.

## Access & Price
- Setiap Content wajib memiliki tepat satu `access_policy`: `free`, `paid`, atau `membership`.
- Content `free` wajib memiliki `price_minor = 0`.
- Perubahan harga tidak berlaku surut terhadap transaksi yang sudah terjadi.

## Public URL & SEO
- `slug` Content harus unik.
- URL publik wajib menggunakan `slug`.
- Metadata SEO dikelola di level `Content`.

## Cross-Module Reference
- Fitur analytics, likes, comments, shares, sponsor, donation, affiliate, notification, recommendation, dan monetization harus mengacu ke `content_id`.
- Snapshot finansial pada Content bukan sumber kebenaran ledger.
