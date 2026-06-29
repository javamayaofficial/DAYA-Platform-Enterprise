# FLOW

## Register
- User submit form register.
- Server validasi input, simpan `users`, assign role default, buat token verifikasi, lalu log email ke `storage/logs/auth-mail.log`.

## Login
- User submit email, password, dan opsional remember me.
- Server cek rate limit, verifikasi hash password, status akun, assign session auth context, simpan device session, dan catat login history.

## Forgot / Reset Password
- User submit email untuk reset.
- Server buat token reset dan kirim tautan reset via mail log.
- User buka tautan dan submit password baru.
- Server validasi token, update password hash, lalu cabut remember token lama.

## Email Verification
- User buka tautan verifikasi.
- Server cek token, expiry, dan status penggunaan.
- Jika valid, akun diaktifkan dan token ditandai `used`.
