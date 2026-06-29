# BUSINESS RULES

## Aggregate
- `Collection` adalah container untuk banyak `Content`.
- `CollectionItem` hanya boleh hidup di bawah satu `Collection`.
- `Collection` tidak menyimpan isi karya, hanya referensi ke `content_id`.

## Ownership
- Setiap `Collection` dimiliki tepat satu `Creator`.
- User harus memiliki profil Creator sebelum dapat membuat `Collection`.
- `Content` yang ditambahkan ke `Collection` wajib milik Creator yang sama.

## Lifecycle
- Lifecycle self-service utama: `draft -> published -> draft`.
- Soft delete mengubah status menjadi `removed` dan menandai `deleted_at`.
- `CollectionItem` aktif ikut ter-soft-delete saat `Collection` dihapus.

## Visibility & URL
- `slug` Collection harus unik.
- URL publik wajib menggunakan `slug`.
- Hanya `Collection` berstatus `published` dan `visibility = public` yang tampil pada halaman publik modul ini.

## Item Ordering
- Satu `Content` tidak boleh aktif dua kali dalam `Collection` yang sama.
- Urutan item dikelola melalui `sort_order`.
- Reorder harus menyertakan seluruh item aktif agar urutan tidak parsial.
