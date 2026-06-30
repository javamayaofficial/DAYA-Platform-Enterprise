# TESTING CHECKLIST — Story Module

## Creator
- Create story dengan title valid → berhasil, status `draft`.
- Create story tanpa creator profile → ditolak.
- Update story → slug unique enforcement berjalan.
- Mark review → status menjadi `review`.
- Publish now → status `published`, `published_at` terisi.
- Schedule → status `scheduled`, `published_at` terisi.
- Archive → status `archived`.
- Duplicate → membuat story baru dengan slug unik.
- Soft delete → `deleted_at` terisi dan tidak muncul di list default.

## Collection Ownership
- Set `collection_id` ke collection milik creator lain → ditolak.

## Public
- `/stories` hanya menampilkan published+public.
- `/stories/{slug}` untuk draft/review/scheduled/archived → 404.

## Admin
- Admin tanpa permission `story.admin.view` → forbidden.
- Admin dengan permission dapat membuka list dan detail.

