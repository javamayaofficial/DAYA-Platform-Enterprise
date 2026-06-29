# TESTING CHECKLIST

## Fungsional
- [ ] Creator yang memiliki profil Creator dapat membuat Content.
- [ ] Creator tanpa profil Creator tidak dapat membuat Content.
- [ ] `slug` Content duplikat ditolak.
- [ ] Creator dapat mengedit Content miliknya sendiri.
- [ ] Creator dapat menambah dan menghapus `Content Part`.
- [ ] Creator dapat submit Content ke moderasi.
- [ ] Admin dapat melihat list dan detail Content.
- [ ] Admin dapat memoderasi Content menjadi `published`, `rejected`, `archived`, atau `removed`.
- [ ] Public directory hanya menampilkan Content `published`.
- [ ] Public detail menggunakan `slug`.
- [ ] Soft delete menghilangkan Content dari public page.

## Arsitektur
- [ ] Semua karya tersimpan sebagai `Content`.
- [ ] `Story`, `Novel`, `Cerpen`, `Artikel`, `Audio`, `Podcast`, dan `Ebook` hanya muncul sebagai `content_type`.
- [ ] Modul atau fitur lain mengacu ke `content_id`, bukan identitas karya alternatif.

## Keamanan
- [ ] Semua form POST memakai CSRF.
- [ ] Creator tidak dapat mengelola Content milik creator lain.
- [ ] Route admin ditolak tanpa permission yang sesuai.
