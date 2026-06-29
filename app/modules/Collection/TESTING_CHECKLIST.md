# TESTING CHECKLIST

## Fungsional
- [ ] Creator yang memiliki profil Creator dapat membuat Collection.
- [ ] Creator tanpa profil Creator tidak dapat membuat Collection.
- [ ] `slug` Collection duplikat ditolak.
- [ ] Creator dapat mengedit metadata Collection miliknya sendiri.
- [ ] Creator hanya dapat menambahkan `Content` miliknya sendiri ke Collection.
- [ ] Satu `Content` tidak bisa aktif dua kali dalam Collection yang sama.
- [ ] Creator dapat mengubah urutan seluruh item Collection.
- [ ] Creator dapat publish dan mengembalikan Collection ke draft.
- [ ] Creator dapat melakukan soft delete Collection.
- [ ] Admin dapat melihat list dan detail Collection.
- [ ] Public directory hanya menampilkan Collection `published/public`.
- [ ] Public detail hanya menampilkan item Content `published/public`.

## Arsitektur
- [ ] `Collection` tidak menyimpan isi karya.
- [ ] Seluruh item Collection mereferensikan `content_id`.
- [ ] Relasi resmi tetap `Creator -> Collection -> CollectionItem -> Content`.

## Keamanan
- [ ] Semua form POST memakai CSRF.
- [ ] Creator tidak dapat mengelola Collection milik creator lain.
- [ ] Route admin ditolak tanpa permission yang sesuai.
