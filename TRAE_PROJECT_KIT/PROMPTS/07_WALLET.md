# PHASE 7 — WALLET
**Status dok:** ✅ READY · **Bergantung:** Phase 2

> ✅ **Modul referensi.** Dokumentasi lengkap. Inilah pola acuan untuk modul lain.

## TUJUAN
Membangun financial core: wallet, credit, transaction, dan ledger double-entry yang append-only & immutable.

## DOKUMEN REFERENSI (WAJIB DIBACA)
- `modules/wallet/BUSINESS_RULES.md` (BR-WALLET-001..101)
- `modules/wallet/FLOW.md` (F1–F6)
- `modules/wallet/DATABASE.md` (skema double-entry, DDL)
- `modules/wallet/API.md`, `modules/wallet/UI.md`
- `modules/wallet/DEVELOPMENT_NOTES.md`, `TESTING_CHECKLIST.md`, `DEPLOYMENT_NOTES.md`
- `DATABASE_STANDARD.md`, `SECURITY_STANDARD.md`

## OUTPUT YANG HARUS DIBUAT
- Migrasi DB (7 file) + seed akun sistem.
- LedgerService (posting seimbang, append-only).
- WalletService (provisioning, topup, spend) + idempotency guard.
- ReconciliationService + cron.
- API & UI wallet sesuai dokumen.

## DEFINITION OF DONE
- Setiap transaksi seimbang (Σ debit = Σ kredit).
- Saldo = agregasi ledger; cache direkonsiliasi.
- Idempotency & no double-spend teruji.
- Seluruh `TESTING_CHECKLIST.md` modul wallet lolos.

## LARANGAN
- ⛔ UPDATE/DELETE pada ledger_entries.
- ⛔ Menambah saldo dari klaim klien (hanya webhook terverifikasi).
- ⛔ Float/decimal untuk perhitungan inti (gunakan integer minor unit).

## HAL YANG TIDAK BOLEH DIUBAH
- Audit & Ledger Principles (double-entry, append-only, immutable, idempotent).
- Representasi uang sebagai integer minor unit.

## CHECKLIST SEBELUM SELESAI
- [ ] Semua item `modules/wallet/TESTING_CHECKLIST.md`.
- [ ] `CHECKLIST/FINANCIAL_CHECKLIST.md`.
- [ ] `CHECKLIST/SECURITY_CHECKLIST.md` & `PRE_COMMIT.md`.
