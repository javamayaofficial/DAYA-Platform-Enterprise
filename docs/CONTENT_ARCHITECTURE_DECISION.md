# CONTENT ARCHITECTURE DECISION

## Status
- Dokumen ini adalah keputusan arsitektur resmi dan permanen untuk bounded context `Content`.
- Berlaku mulai sebelum implementasi `Content Module` dan menjadi acuan seluruh modul lanjutan yang berhubungan dengan karya.

## Decision 1: Content Adalah Entitas Utama Seluruh Karya
- Semua karya di DAYA Platform berasal dari satu entitas utama bernama `Content`.
- `Content` adalah aggregate root untuk metadata, lifecycle publikasi, model akses, SEO, dan identitas publik karya.

## Decision 2: Jenis Karya Adalah Content Type
- `Story`, `Novel`, `Cerpen`, `Artikel`, `Audio`, `Podcast`, `Ebook`, dan tipe karya lain diperlakukan sebagai `content_type`.
- Tipe tersebut bukan modul database mandiri dan bukan tabel domain terpisah per jenis karya.
- Perbedaan perilaku atau presentasi harus diturunkan dari `content_type`, bukan dari pecahan modul baru.

## Decision 3: Semua Fitur Turunan Mengacu Ke Content
- Fitur berikut wajib mengacu ke `content_id`:
  - SEO
  - Analytics
  - Views
  - Likes
  - Comments
  - Shares
  - Wallet Revenue
  - Sponsor
  - Donation
  - Affiliate
  - Recommendation
  - Search
- Modul lain tidak boleh memperkenalkan identitas karya paralel di luar `Content`.

## Decision 4: Creator Memiliki Banyak Content
- Relasi utama ownership adalah `Creator -> many Content`.
- Content tidak dimiliki langsung oleh user biasa; kepemilikan karya diikat ke `creator_id`.

## Decision 5: Modul Lain Harus Mengacu Ke Content ID
- `Wallet`, `Payment`, `Sponsor`, `Foundation`, `Analytics`, `Notification`, dan `Affiliate` wajib menyimpan referensi ke `content_id` saat berhubungan dengan karya.
- Jika nanti dibutuhkan reference tambahan seperti `creator_id`, `transaction_id`, atau `campaign_id`, `content_id` tetap menjadi foreign reference utama ke karya.

## Decision 6: Content Part Bukan Modul Terpisah
- `Content Part` tetap boleh ada sebagai sub-entitas internal milik `Content`.
- `Content Part` tidak boleh diimplementasikan sebagai modul domain yang berdiri sendiri.
- Akses `Content Part` harus selalu melalui agregat `Content`.

## Decision 7: SEO-Friendly Public Profile Menggunakan Slug Content
- URL publik karya wajib menggunakan `slug` milik `Content`.
- `slug` harus unik pada scope publik yang digunakan sistem.

## Decision 8: Moderasi Tetap Terpusat Pada Content
- Workflow moderasi seperti `draft`, `in_review`, `published`, `rejected`, `archived`, dan `removed` dikelola pada level `Content`.
- Fitur publik tidak boleh menampilkan karya selain `published`.

## Konsekuensi Arsitektural
- Pembangunan modul turunannya menjadi lebih konsisten karena seluruh aktivitas karya berporos pada `content_id`.
- Penambahan tipe karya baru tidak memerlukan modul atau tabel utama baru; cukup menambah `content_type`.
- Analytics, recommendation, monetization, dan search dapat berkembang tanpa mengubah model kepemilikan karya.

## Larangan Permanen
- Dilarang membuat modul database terpisah seperti `Story Module`, `Podcast Module`, atau `Ebook Module` sebagai root karya utama.
- Dilarang membuat foreign reference karya yang tidak menunjuk ke `content_id` ketika konteks bisnisnya adalah karya Creator.
- Dilarang menampilkan Content non-`published` ke publik.
