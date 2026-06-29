# BUSINESS RULES

- `BR-AUTH-001`: Email user wajib unik.
- `BR-AUTH-002`: Password wajib di-hash dengan `password_hash()` dan tidak pernah disimpan plaintext.
- `BR-AUTH-003`: User baru berstatus `pending_verification` sampai email berhasil diverifikasi.
- `BR-AUTH-004`: User hanya boleh login jika status akun `active` dan email sudah terverifikasi.
- `BR-AUTH-005`: Login gagal berulang dari kombinasi email + IP dibatasi oleh rate limit.
- `BR-AUTH-006`: Setiap login sukses/gagal/blocked wajib tercatat di `login_history`.
- `BR-AUTH-007`: Remember Me menggunakan token selector/validator yang di-hash dan dapat dicabut.
- `BR-AUTH-008`: Setiap login membentuk `device_session`; session lama dapat dicabut per perangkat.
- `BR-AUTH-009`: Satu user dapat memiliki banyak role sekaligus.
- `BR-AUTH-010`: Permission diperoleh dari role; `super_admin` memiliki bypass permission.
- `BR-AUTH-011`: Reset password hanya sah jika token valid, belum terpakai, dan belum kedaluwarsa.
- `BR-AUTH-012`: Setelah reset password, remember token lama dicabut.
