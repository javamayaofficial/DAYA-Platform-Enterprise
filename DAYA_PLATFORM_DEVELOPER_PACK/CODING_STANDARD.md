# CODING STANDARD — DAYA PLATFORM

> Standar penulisan kode wajib. Turunan dari PROJECT_CONSTITUTION §8.

## 1. NAMING CONVENTION
| Elemen | Konvensi | Contoh |
|---|---|---|
| Class | PascalCase | `WalletService` |
| File Class | Sama dengan class | `WalletService.php` |
| Method/Function | camelCase | `calculateSplit()` |
| Variabel | camelCase | `$creatorShare` |
| Konstanta | UPPER_SNAKE_CASE | `MISSION_DEFAULT_RATE` |
| Tabel DB | snake_case jamak | `wallet_transactions` |
| Kolom DB | snake_case | `amount_minor` |
| Foreign Key | `<entitas>_id` | `wallet_id` |
| URL/Route | kebab-case | `/creator-dashboard` |
| View/Config | snake_case | `dashboard_view.php` |

## 2. STRUKTUR & MODULARITAS
- Satu modul = self-contained (`controllers/services/models/views`).
- Komunikasi antar-modul lewat service/interface, bukan akses internal langsung.
- **Thin Controller, Rich Service**: controller hanya mengatur alur; logika bisnis di service.
- Repository memisahkan akses data dari logika bisnis.

## 3. CLEAN CODE
- Satu fungsi, satu tanggung jawab (Single Responsibility).
- Nama menjelaskan maksud; komentar menjelaskan **mengapa**, bukan **apa**.
- Tanpa magic number — gunakan konstanta/konfigurasi.
- Batasi panjang & kompleksitas fungsi; pecah bila perlu.

## 4. REUSABLE / DRY
- Logika berulang diangkat ke helper/service.
- Dilarang menyalin-tempel logika bisnis.
- Komponen UI berulang dibuat partial view.

## 5. ERROR HANDLING & LOGGING
- Tangani error secara eksplisit; jangan menelan exception diam-diam.
- Operasi finansial **gagal terang-terangan** (fail loud), tidak meninggalkan state setengah jadi.
- Logging untuk aksi penting; audit trail untuk perubahan data sensitif.

## 6. PRINSIP FINANSIAL (WAJIB UNTUK MODUL UANG)
- Double-entry, append-only, immutable (lihat DATABASE_STANDARD & Wallet).
- Idempotency untuk setiap operasi nilai.
- Saldo diturunkan dari ledger, tidak dimutasi bebas.
