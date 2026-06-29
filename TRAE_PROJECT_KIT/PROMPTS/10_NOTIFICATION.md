# PHASE 10 — NOTIFICATION
**Status dok:** 🔴 DRAFT · **Bergantung:** Phase 2

> ⚠️ **GATE DOKUMENTASI (🔴 DRAFT):** Dokumen modul ini masih scaffold. **JANGAN menulis kode** sebelum dokumen modul dilengkapi mengikuti `MODULE_TEMPLATE.md` dan disetujui. Jika belum lengkap → **berhenti & minta pelengkapan.**

## TUJUAN
Sistem notifikasi multi-channel (in-app, email, push/WA) berbasis event.

## DOKUMEN REFERENSI (WAJIB DIBACA)
- `modules/notification/*` (lengkapi dulu)
- `DOMAIN_MODEL.md` (Notification 6.21)
- `MASTER_BLUEPRINT.md` (#23)

## OUTPUT YANG HARUS DIBUAT
- Katalog notifikasi & pemicu (event consumer).
- Template + lokalisasi.
- Queue & pengiriman via **cron** (bukan daemon).

## DEFINITION OF DONE
- Notifikasi finansial tidak hilang (at-least-once).
- Preferensi kanal dihormati.

## LARANGAN
- ⛔ Notification menjadi sumber kebenaran data.
- ⛔ Mengirim tanpa menghormati preferensi user.

## HAL YANG TIDAK BOLEH DIUBAH
- Notification sebagai event consumer (read-only terhadap domain lain).

## CHECKLIST SEBELUM SELESAI
- [ ] Cron pengiriman berfungsi.
- [ ] Template teruji.
- [ ] `PRE_COMMIT.md`.
