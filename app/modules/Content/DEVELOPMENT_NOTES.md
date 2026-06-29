# DEVELOPMENT NOTES

## Arsitektur
- `Content` adalah aggregate root seluruh karya.
- `content_type` adalah klasifikasi karya, bukan modul database terpisah.
- `Content Part` diperlakukan sebagai sub-entitas internal milik Content.
- Moderasi publikasi dipusatkan pada status Content.

## Reuse
- Reuse `Creator` sebagai owner relation melalui `creator_id`.
- Reuse `Authentication` untuk auth, CSRF, dan permission admin review.
- Reuse `Base*` modular foundation tanpa perubahan pada core.

## Catatan
- Angka revenue, sponsor, donation, affiliate, dan recommendation di tabel Content adalah snapshot/reference untuk modul lain yang akan mengacu ke `content_id`.
- Kebenaran finansial tetap berada pada modul ledger/keuangan saat dibangun nanti.
