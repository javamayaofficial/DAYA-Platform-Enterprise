# FLOW — Story Module

## Creator Flow
1. Creator membuka dashboard `/story`.
2. Klik create → isi form → submit.
3. Service:
   - resolve creator profile
   - validate payload
   - generate/normalize slug
   - hitung word count & reading time
   - simpan story
4. Detail `/story/{id}` untuk aksi lanjutan:
   - review
   - publish now
   - schedule
   - archive
   - duplicate
   - soft delete

## Public Flow
1. User publik membuka `/stories`.
2. Klik item → `/stories/{slug}`.
3. Service mengambil story publik by slug (published+public).

## Admin Flow
1. Admin membuka `/story/admin` (permission `story.admin.view`).
2. Admin dapat melihat detail `/story/admin/{id}`.

