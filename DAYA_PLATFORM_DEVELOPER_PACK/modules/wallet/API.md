# WALLET MODULE — API

> Kontrak REST API modul Wallet. Mengikuti API_STANDARD. **Endpoint mutasi nilai wajib idempotent.**

## ENDPOINTS
| Method | Path | Auth | Deskripsi |
|---|---|---|---|
| GET | `/api/v1/wallet` | User | Saldo & ringkasan wallet milik user |
| GET | `/api/v1/wallet/transactions` | User | Riwayat transaksi (paginated) |
| GET | `/api/v1/wallet/transactions/{id}` | User | Detail satu transaksi |
| POST | `/api/v1/wallet/topup` | User | Inisiasi top-up (lanjut ke Payment) |
| POST | `/api/v1/wallet/spend` | User/System | Debit untuk pembelian (idempotent) |
| GET | `/api/v1/wallet/credits` | User | Daftar batch credit & masa berlaku |
| POST | `/api/v1/admin/wallet/{id}/freeze` | Admin | Bekukan wallet (alasan wajib) |
| POST | `/api/v1/admin/wallet/{id}/unfreeze` | Admin | Cabut pembekuan |

## IDEMPOTENCY
- Header wajib pada `topup`/`spend`: `Idempotency-Key: <uuid>`.
- Permintaan ganda mengembalikan hasil sama (HTTP 200) tanpa proses ulang; bentrok → HTTP 409.

## CONTOH RESPONS — GET /wallet
```json
{
  "success": true,
  "data": {
    "wallet_id": 123,
    "status": "active",
    "currency": "IDR",
    "balance_minor": 15000000,
    "balance_display": "150.000"
  },
  "message": "OK"
}
```

## ERROR UMUM
| Code | HTTP | Makna |
|---|---|---|
| INSUFFICIENT_BALANCE | 422 | Saldo tidak cukup (BR-011/060) |
| WALLET_FROZEN | 403 | Wallet dibekukan (BR-003) |
| DUPLICATE_REQUEST | 409 | Idempotency key sudah dipakai |
| VALIDATION_ERROR | 422 | Input tidak valid |

## CATATAN
- Saldo dikembalikan sebagai `balance_minor` (integer) + `balance_display` (terformat).
- Tidak ada endpoint yang mengubah ledger secara langsung; semua melalui transaksi.
