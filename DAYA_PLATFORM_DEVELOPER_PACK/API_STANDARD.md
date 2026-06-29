# API STANDARD — DAYA PLATFORM

> Standar kontrak REST API (mobile-first & integrasi eksternal).

## 1. KONVENSI UMUM
- Prefix versi: `/api/v1`.
- Resource jamak & kebab-case: `/api/v1/wallets`, `/api/v1/wallet-transactions`.
- Verb HTTP semantik: `GET` (baca), `POST` (buat), `PUT/PATCH` (ubah), `DELETE` (hapus/nonaktif).
- Stateless: setiap request membawa autentikasinya.

## 2. AUTENTIKASI & OTORISASI
- Token-based (mis. Bearer token/JWT) pada header `Authorization`.
- Otorisasi berbasis RBAC (lihat SECURITY_STANDARD).
- Endpoint sensitif memeriksa kepemilikan resource.

## 3. FORMAT RESPONS (AMPLOP KONSISTEN)
```json
{
  "success": true,
  "data": { },
  "message": "OK",
  "meta": { "page": 1, "per_page": 20, "total": 0 }
}
```
Error:
```json
{
  "success": false,
  "error": { "code": "INSUFFICIENT_BALANCE", "message": "Saldo tidak cukup" }
}
```

## 4. STATUS CODE
| Kode | Makna |
|---|---|
| 200/201 | Sukses / Dibuat |
| 400 | Input tidak valid |
| 401/403 | Tidak terautentikasi / Tidak berwenang |
| 404 | Tidak ditemukan |
| 409 | Konflik (mis. idempotency/duplikat) |
| 422 | Validasi gagal |
| 429 | Rate limit terlampaui |
| 500 | Kesalahan server |

## 5. ATURAN KHUSUS FINANSIAL
- Endpoint mutasi nilai **wajib** menerima `Idempotency-Key` (header/body).
- Permintaan duplikat mengembalikan hasil sama tanpa proses ulang.
- Tidak mempercayai data klien untuk menambah nilai (verifikasi server/gateway).

## 6. LAIN-LAIN
- Pagination untuk koleksi besar (`page`, `per_page`).
- Rate limiting pada endpoint publik & sensitif.
- Validasi input server-side untuk semua endpoint.
- Versioning: perubahan breaking → naikkan versi API.
