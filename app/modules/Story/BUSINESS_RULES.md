# BUSINESS RULES — Story Module

## Ownership
- Story selalu dimiliki oleh satu Creator (`creator_id`).
- Akses create/manage Story untuk user hanya valid jika user memiliki Creator Profile.

## Collection (Opsional)
- `collection_id` boleh `NULL`.
- Jika `collection_id` diisi, maka collection harus milik creator yang sama.

## Slug
- Slug otomatis dibuat dari title jika kosong.
- Slug wajib unik secara global.

## Status
- `draft`: default saat create.
- `review`: creator menandai siap direview.
- `scheduled`: publish di waktu tertentu (`published_at`).
- `published`: publikasi aktif.
- `archived`: tidak aktif, tidak muncul di public.

## Visibility
- `public`: dapat tampil di public.
- `private`: hanya creator.
- `subscriber`, `premium`: placeholder; belum ada implementasi akses berlangganan.

## Public Availability
Public endpoint hanya boleh menampilkan story jika:
- `status = published`
- `visibility = public`
- `deleted_at IS NULL`

## Soft Delete
- Delete tidak menghapus baris, hanya mengisi `deleted_at`.

