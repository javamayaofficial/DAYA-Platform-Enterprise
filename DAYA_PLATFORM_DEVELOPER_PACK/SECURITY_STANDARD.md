# SECURITY STANDARD — DAYA PLATFORM

> Keamanan adalah Security by Design, bukan tambahan akhir. Turunan dari Constitution §8.3 & Blueprint #20.

## 1. INPUT & DATA
- **Validasi & sanitasi server-side wajib** untuk seluruh input (client-side hanya pelengkap).
- **PDO prepared statements** untuk semua kueri; dilarang konkatenasi SQL.
- Output encoding untuk mencegah XSS.

## 2. AUTENTIKASI & SESI
- Password di-hash dengan `password_hash()` (bcrypt/argon2).
- Manajemen sesi aman (regenerasi ID, flag httpOnly/secure).
- Token API disimpan & diverifikasi dengan aman.

## 3. OTORISASI
- RBAC dengan prinsip **least privilege**.
- Periksa kepemilikan resource pada setiap aksi sensitif.

## 4. PROTEKSI APLIKASI
- **CSRF token** pada semua form yang mengubah state.
- Rate limiting untuk login & endpoint sensitif.
- Validasi & batasi upload file (tipe, ukuran, lokasi di luar web root).
- HTTPS wajib di produksi.

## 5. RAHASIA & KONFIGURASI
- Kredensial/API key **tidak pernah** masuk repo.
- Konfigurasi rahasia di luar web root.

## 6. AUDIT & FINANSIAL
- **Audit trail** untuk aksi penting (perubahan data sensitif, freeze wallet, payout).
- Append-only & immutable untuk ledger (lihat DATABASE_STANDARD).
- Idempotency untuk operasi nilai.

## 7. OWASP TOP 10
Setiap fitur diperiksa terhadap OWASP Top 10 (injection, broken auth, broken access control, dsb) sebelum rilis.
