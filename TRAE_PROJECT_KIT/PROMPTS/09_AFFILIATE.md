# PHASE 9 — AFFILIATE
**Status dok:** 🔴 DRAFT · **Bergantung:** Phase 7

> ⚠️ **GATE DOKUMENTASI (🔴 DRAFT):** Dokumen modul ini masih scaffold. **JANGAN menulis kode** sebelum dokumen modul dilengkapi mengikuti `MODULE_TEMPLATE.md` dan disetujui. Jika belum lengkap → **berhenti & minta pelengkapan.**

## TUJUAN
Sistem afiliasi: referral, atribusi, komisi (via Revenue Sharing), dan anti-fraud.

## DOKUMEN REFERENSI (WAJIB DIBACA)
- `modules/affiliate/*` (lengkapi dulu)
- `DOMAIN_MODEL.md` (Affiliate 6.15, Referral 6.16, Revenue Sharing 6.14)
- `modules/wallet/*` (pembukuan komisi)

## OUTPUT YANG HARUS DIBUAT
- Referral & atribusi (window, last-click sesuai aturan).
- Perhitungan komisi via Revenue Sharing → ledger.
- Anti-fraud (self-referral dilarang, validasi konversi).

## DEFINITION OF DONE
- Komisi hanya atas konversi valid & lolos fraud check.
- Pembukuan komisi tercatat di ledger.

## LARANGAN
- ⛔ Membayar komisi self-referral.
- ⛔ Menghitung komisi di luar Revenue Sharing.

## HAL YANG TIDAK BOLEH DIUBAH
- Total alokasi = nilai dasar (tidak ada nilai tercipta/hilang).

## CHECKLIST SEBELUM SELESAI
- [ ] Anti-fraud teruji.
- [ ] Pembukuan komisi seimbang.
- [ ] `FINANCIAL_CHECKLIST.md`, `PRE_COMMIT.md`.
