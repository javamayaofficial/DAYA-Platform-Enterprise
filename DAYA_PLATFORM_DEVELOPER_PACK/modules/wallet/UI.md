# WALLET MODULE — UI

> Layar mobile-first berbasis Bootstrap 5. Mengikuti UI_STANDARD.

## LAYAR
| Layar | Tujuan |
|---|---|
| Wallet Overview | Menampilkan saldo, ringkasan, tombol top-up |
| Transaction History | Daftar transaksi (paginated, filter) |
| Transaction Detail | Rincian satu transaksi |
| Top-up Flow | Memilih nominal → lanjut ke Payment |
| Credit List | Batch credit & masa berlaku |
| Admin: Wallet Control | Freeze/unfreeze (alasan wajib) |

## STATE WAJIB
Setiap layar menangani: **loading**, **empty**, **error**, **success**.

## ATURAN TAMPILAN UANG
- Saldo diterima sebagai integer minor unit → **dikonversi & diformat** (mis. `15000000` → `Rp150.000`).
- Tidak pernah menghitung ulang saldo di sisi klien; selalu dari server.

## KOMPONEN
- Card saldo (menonjol, jelas).
- Tabel/daftar transaksi responsif (badge status: pending/completed/failed/reversed).
- Form top-up dengan validasi nominal (min/maks sesuai konfigurasi).
- Dialog konfirmasi untuk aksi admin (freeze) dengan field alasan wajib.

## AKSESIBILITAS
- Kontras memadai untuk angka finansial.
- Label jelas pada nominal & status.
