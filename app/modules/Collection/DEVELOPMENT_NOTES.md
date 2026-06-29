# DEVELOPMENT NOTES

## Arsitektur
- `Collection` diperlakukan sebagai aggregate root untuk pengelompokan Content.
- `CollectionItem` adalah child entity terurut yang mereferensikan `content_id`.
- Halaman publik tetap menghormati status publik Collection dan Content.

## Reuse
- Reuse `Creator` sebagai owner relation melalui `creator_id`.
- Reuse `Content` sebagai sumber item tanpa menyalin body karya.
- Reuse `Authentication` untuk auth, CSRF, dan permission admin monitoring.
- Reuse `Base*` modular foundation tanpa perubahan pada core.

## Catatan
- Modul ini sengaja tidak membuat `Series`, `Universe`, `Playlist`, `Recommendation`, `Search`, atau `Analytics`.
- Duplikasi item aktif dicegah di service sebelum insert ke `collection_items`.
- Soft delete Collection juga menandai semua item aktif sebagai deleted.
