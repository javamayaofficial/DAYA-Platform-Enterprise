# DATABASE STANDARD — DAYA PLATFORM

> Standar database wajib (MySQL/InnoDB), dioptimalkan untuk shared hosting.

## 1. PRINSIP UMUM
- Engine **InnoDB** (mendukung transaksi & foreign key).
- Charset `utf8mb4`.
- Tabel: `snake_case` jamak. Kolom: `snake_case`. PK: `id`. FK: `<entitas>_id`.
- Timestamp `created_at` / `updated_at` (DATETIME).
- Hindari penghapusan fisik untuk data penting; gunakan status/soft-delete bila relevan (kecuali ledger yang append-only).

## 2. UANG & FINANSIAL
- Uang disimpan sebagai **integer minor unit** (`BIGINT`) + kolom `currency` (CHAR(3)).
- **Dilarang** float/decimal untuk perhitungan saldo finansial inti.
- Modul finansial wajib **double-entry**: setiap transaksi seimbang (Σ debit = Σ kredit).
- **Ledger append-only & immutable**: tanpa UPDATE/DELETE; koreksi via entri kompensasi (reversal).
- Saldo adalah turunan ledger; cache saldo wajib dapat direkonsiliasi.
- Idempotency via kolom UNIQUE (`idempotency_key`).

## 3. INTEGRITAS
- Operasi multi-tabel dibungkus transaksi DB (BEGIN/COMMIT) + row locking.
- Foreign key untuk relasi wajib.
- Validasi tambahan di service layer (terutama bila hosting tak mendukung CHECK constraint).
- Least privilege: user DB aplikasi sebaiknya tanpa hak UPDATE/DELETE pada tabel ledger.

## 4. AKSES DATA
- **Wajib PDO prepared statements.** Dilarang konkatenasi string SQL.
- Indeks untuk kolom yang sering difilter/di-join.
- Hindari N+1 query; gunakan join/batch.

## 5. MIGRASI (TANPA CLI)
- File SQL bernomor di `database/migrations` (mis. `001_create_wallets.sql`).
- Diterapkan via **phpMyAdmin / File Manager** cPanel/FastPanel.
- Setiap perubahan skema **wajib** memperbarui `DATABASE.md` modul terkait lebih dulu.

## 6. EVOLUSI
- Siapkan jalur partisi/arsip untuk tabel ledger besar.
- Desain menyiapkan kolom scoping tenant untuk evolusi multi-tenant.
