# WALLET MODULE — DEVELOPMENT NOTES

> Catatan teknis implementasi. **Tanpa kode** — hanya panduan untuk developer/TRAE.

## URUTAN IMPLEMENTASI
1. Skema DB (jalankan migrasi sesuai DATABASE.md) + akun sistem awal (seed).
2. Repository (akses data) untuk wallets, transactions, ledger_entries, credit_batches.
3. Service inti: `LedgerService` (posting double-entry seimbang), `WalletService` (provisioning, saldo, spend, topup), `ReconciliationService`.
4. Middleware: Auth + RBAC + idempotency guard.
5. Controller (thin) + routes.
6. UI sesuai UI.md.

## TANGGUNG JAWAB SERVICE
- **LedgerService**: satu-satunya yang memposting entri; memastikan Σ debit = Σ kredit dalam satu transaksi DB.
- **WalletService**: orkestrasi operasi nilai; tidak menyentuh ledger langsung selain via LedgerService.
- **ReconciliationService**: dipanggil cron; bandingkan cache vs ledger.

## KEPUTUSAN TEKNIS PENTING
- Uang = integer minor unit (BIGINT) di seluruh lapisan; konversi hanya di tampilan.
- Idempotency: simpan & cek `idempotency_key` sebelum memproses.
- Saldo: bungkus operasi dalam transaksi DB + row lock pada wallet.
- Append-only: aplikasi tidak melakukan UPDATE/DELETE pada ledger_entries.

## JEBAKAN UMUM
- Jangan menambah saldo dari klaim klien — hanya dari webhook Payment terverifikasi.
- Jangan menghitung saldo dengan menjumlahkan kolom yang bisa berubah; gunakan ledger.
- Reversal bukan "edit" — selalu transaksi baru bertipe `reversal`.

## KETERGANTUNGAN
- Identity/Authentication (pemilik wallet).
- Payment (top-up & withdraw eksternal).
- Revenue Sharing (alokasi saat spend).
- Configuration Engine (batas top-up, masa berlaku credit).
