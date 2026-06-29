# WALLET MODULE — DEPLOYMENT NOTES

> Deployment via cPanel / FastPanel. Tanpa Docker/SSH/Terminal.

## MIGRASI DATABASE (URUTAN)
1. `001_create_accounts.sql`
2. `002_create_wallets.sql`
3. `003_create_transactions.sql`
4. `004_create_ledger_entries.sql`
5. `005_create_credit_batches.sql`
6. `006_create_wallet_audit_log.sql`
7. `007_seed_system_accounts.sql` (PLATFORM_REVENUE, MISSION_FUND, GATEWAY_CLEARING)

Diterapkan via **phpMyAdmin** (import) atau File Manager. Verifikasi InnoDB & utf8mb4.

## KONFIGURASI
- Kredensial DB & rahasia di file konfigurasi **di luar web root**.
- Parameter konfigurabel (batas top-up, masa berlaku credit) via Configuration Engine.

## CRON (cPanel/FastPanel)
| Cron | Frekuensi | Fungsi |
|---|---|---|
| Reconciliation | Harian | Selaraskan cache saldo ke ledger (BR-100) |
| Credit Expiry | Harian | Tandai credit kedaluwarsa & bukukan ekspirasi |
| Pending TTL Sweep | Tiap jam | Tandai transaksi pending kedaluwarsa → failed |

## HAK AKSES DB (REKOMENDASI)
- User DB aplikasi: tanpa privilege UPDATE/DELETE pada `ledger_entries` (penegakan append-only).
- Bila hosting tak mendukung granular privilege, tegakkan via service layer + trigger.

## SMOKE TEST PASCA-DEPLOY
- [ ] Buat user uji → wallet otomatis ada.
- [ ] Simulasi top-up terverifikasi → saldo bertambah & ledger seimbang.
- [ ] Spend → saldo berkurang, alokasi terposting.
- [ ] Cek rekonsiliasi tidak menemukan selisih.

## BACKUP & ROLLBACK
- Backup penuh DB **sebelum** migrasi.
- Rollback: restore backup; ledger tidak boleh diutak-atik manual.
