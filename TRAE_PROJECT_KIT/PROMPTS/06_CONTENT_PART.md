# PHASE 6 — CONTENT PART
**Status dok:** 🔴 DRAFT · **Bergantung:** Phase 5

> ⚠️ **GATE DOKUMENTASI (🔴 DRAFT):** Dokumen modul ini di Developer Pack masih scaffold. **JANGAN menulis kode** sebelum dokumen modul (BUSINESS_RULES/FLOW/DATABASE/API/UI/dst) dilengkapi mengikuti `MODULE_TEMPLATE.md` dan disetujui. Jika belum lengkap → **berhenti & minta pelengkapan.**

## TUJUAN
Menyusun content menjadi bagian terurut (bab/modul/episode) dengan rilis bertahap.

## DOKUMEN REFERENSI (WAJIB DIBACA)
- `modules/part/*` (lengkapi dulu)
- `_domain_reference/CONTENT-DOMAIN.md` (Content Part)

## OUTPUT YANG HARUS DIBUAT
- Part terurut di bawah Content (aggregate root).
- Drip release (jadwal rilis).

## DEFINITION OF DONE
- Part mewarisi access policy induk.
- Urutan & jadwal rilis benar.

## LARANGAN
- ⛔ Mengakses Part di luar hak akses induk.

## HAL YANG TIDAK BOLEH DIUBAH
- Content sebagai aggregate root atas Part.

## CHECKLIST SEBELUM SELESAI
- [ ] Pewarisan akses teruji.
- [ ] Jalankan `PRE_COMMIT.md`.
