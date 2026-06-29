# PHASE 8 — PAYMENT (DUITKU)
**Status dok:** 🔴 DRAFT · **Bergantung:** Phase 7

> ⚠️ **GATE DOKUMENTASI (🔴 DRAFT):** Dokumen modul ini masih scaffold. **JANGAN menulis kode** sebelum dokumen modul dilengkapi mengikuti `MODULE_TEMPLATE.md` dan disetujui. Jika belum lengkap → **berhenti & minta pelengkapan.**

## TUJUAN
Mengintegrasikan gateway **Duitku** untuk dana masuk (top-up/pembelian) & pencairan (withdraw), terhubung ke Wallet via ledger.

## DOKUMEN REFERENSI (WAJIB DIBACA)
- `modules/payment/*` (lengkapi dulu)
- `modules/wallet/*` (titik integrasi: topup/withdraw)
- `DOMAIN_MODEL.md` (Payment 6.12, Withdraw 6.13)
- `API_STANDARD.md`, `SECURITY_STANDARD.md`

## OUTPUT YANG HARUS DIBUAT
- Integrasi Duitku via **Anti-Corruption Layer** (penerjemah, agar internal bersih).
- Alur checkout & instruksi pembayaran.
- **Webhook idempotent**: hanya status terverifikasi yang menambah nilai.
- Alur withdraw/disbursement → posting ledger.

## DEFINITION OF DONE
- Top-up sukses hanya menambah nilai setelah verifikasi gateway.
- Webhook ganda tidak menambah nilai dua kali (idempotent).
- Withdraw memengaruhi saldo sesuai BR-WALLET-070..072.

## LARANGAN
- ⛔ Mempercayai callback/redirect klien sebagai bukti bayar.
- ⛔ Menyimpan rahasia Duitku di repo/web root.

## HAL YANG TIDAK BOLEH DIUBAH
- Idempotency & verifikasi server-side.
- Audit & Ledger Principles.

## CHECKLIST SEBELUM SELESAI
- [ ] Webhook idempotent teruji (kirim ganda).
- [ ] Rahasia gateway aman (di luar web root).
- [ ] `FINANCIAL_CHECKLIST.md`, `SECURITY_CHECKLIST.md`, `PRE_COMMIT.md`.
