# FLOW

## Pembuatan Content
1. Creator membuka `/content/create`.
2. Creator mengisi metadata karya, `content_type`, body, access policy, dan SEO.
3. Service memvalidasi dan membuat `Content` dengan status awal `draft`.

## Pengelolaan Part
1. Creator membuka detail Content miliknya.
2. Creator menambah `Content Part` ke karya yang sama.
3. Part hanya hidup di bawah `content_id` induknya.

## Moderasi
1. Creator mengajukan Content ke `/content/{id}/submit`.
2. Status berubah menjadi `in_review`.
3. Admin melakukan moderasi di `/content/admin/{id}`.
4. Hanya Content `published` yang ditampilkan publik.

## Halaman Publik
1. Visitor membuka `/contents`.
2. Search dan listing hanya memuat Content `published`.
3. Detail publik diakses via `/contents/{slug}`.
4. Saat halaman publik dibuka, `views_count` bertambah.
