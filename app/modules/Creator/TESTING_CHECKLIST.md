# TESTING CHECKLIST

## Fungsional
- [ ] User login dapat membuat Creator baru sekali.
- [ ] Creator code dan slug dibuat unik.
- [ ] Handle duplikat ditolak saat create dan edit.
- [ ] Slug duplikat ditolak saat create dan edit.
- [ ] Creator dapat mengubah profil inti.
- [ ] Creator dapat mengubah avatar, cover, SEO, multi category, dan multi skill.
- [ ] Creator dapat mengubah setting publik.
- [ ] Creator dapat menambah dan menghapus social link.
- [ ] Creator dapat menambah dan menghapus typed portfolio.
- [ ] Creator dapat menambah dan menghapus achievement.
- [ ] Admin dapat mencari Creator berdasarkan kata kunci.
- [ ] Admin dapat memfilter list berdasarkan status, kategori, dan deleted flag.
- [ ] Pagination list admin dan public berjalan.
- [ ] Admin dapat mengubah status review, verification status, level, rank, badge, dan statistik snapshot.
- [ ] Public directory hanya menampilkan Creator aktif dengan halaman publik aktif.
- [ ] Public profile memakai slug dan menampilkan badge/skill/achievement sesuai data.
- [ ] Soft delete menghilangkan Creator dari public page.

## Keamanan
- [ ] Semua form POST Creator memiliki CSRF token.
- [ ] Route admin Creator ditolak tanpa permission yang sesuai.
- [ ] User tidak dapat mengelola Creator milik user lain.

## Edge Cases
- [ ] User yang sudah pernah punya Creator tidak bisa mendaftar Creator kedua.
- [ ] Soft deleted Creator tetap tidak membuka pendaftaran baru.
- [ ] Portfolio featured terbaru menonaktifkan featured item sebelumnya.
- [ ] Statistik finansial di Creator hanya diperlakukan sebagai snapshot, bukan sumber kebenaran ledger.
