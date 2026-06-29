# FLOW

## Pembuatan Collection
1. Creator membuka `/collection/create`.
2. Creator mengisi metadata `Collection` seperti title, slug, description, cover, dan visibility.
3. Service memvalidasi dan membuat `Collection` dengan status awal `draft`.

## Pengelolaan Item
1. Creator membuka detail Collection miliknya di `/collection/{id}`.
2. Creator menambahkan `Content` miliknya sendiri ke Collection.
3. Creator dapat menghapus item atau mengubah `sort_order` seluruh item aktif.

## Publish / Draft
1. Creator menekan aksi publish dari detail Collection.
2. Service mengubah status menjadi `published` dan menetapkan `published_at`.
3. Creator dapat mengembalikan Collection ke `draft`.

## Halaman Publik
1. Visitor membuka `/collections`.
2. Listing hanya memuat Collection `published/public`.
3. Detail publik diakses via `/collections/{slug}`.
4. Halaman detail publik hanya menampilkan item Content `published/public`.

## Monitoring Admin
1. Admin membuka `/collection/admin`.
2. Admin memfilter Collection berdasarkan status, visibility, dan deleted flag.
3. Admin membuka detail untuk melihat metadata Collection dan item aktifnya.
