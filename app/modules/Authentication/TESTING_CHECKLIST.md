# TESTING CHECKLIST

## Fungsional
- [ ] Register user baru berhasil.
- [ ] Email duplikat ditolak.
- [ ] Login dengan akun belum terverifikasi ditolak.
- [ ] Login dengan akun aktif berhasil.
- [ ] Logout menghapus session aktif.
- [ ] Forgot password membuat token reset.
- [ ] Reset password hanya berhasil dengan token valid.
- [ ] Email verification mengubah status akun menjadi `active`.
- [ ] Remember me membuat cookie dan dapat restore session.
- [ ] Device session tercatat saat login.
- [ ] Device session non-current dapat dicabut.
- [ ] Login history tercatat untuk sukses, gagal, dan blocked.
- [ ] Assign multi-role user berhasil.
- [ ] Permission middleware menolak akses yang tidak berwenang.

## Keamanan
- [ ] Password tersimpan sebagai hash.
- [ ] Semua form POST memiliki CSRF token.
- [ ] Login rate limit bekerja untuk percobaan berulang.
- [ ] Remember token di database tersimpan sebagai hash validator.
- [ ] Session ID diregenerasi saat login.

## Edge Cases
- [ ] Token verifikasi kedaluwarsa ditolak.
- [ ] Token reset terpakai ulang ditolak.
- [ ] User suspended/deactivated/banned tidak bisa login.
