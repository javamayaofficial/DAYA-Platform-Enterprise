# PHASE 3 — ADMIN DASHBOARD
**Status dok:** 🔴 DRAFT · **Bergantung:** Phase 1, 2

> ⚠️ **GATE DOKUMENTASI (🔴 DRAFT):** Dokumen modul ini di Developer Pack masih scaffold. **JANGAN menulis kode** sebelum dokumen modul (BUSINESS_RULES/FLOW/DATABASE/API/UI/dst) dilengkapi mengikuti `MODULE_TEMPLATE.md` dan disetujui. Jika belum lengkap → **berhenti & minta pelengkapan.**

## TUJUAN
Menyediakan back-office untuk operasional, moderasi, dan kontrol — berbasis RBAC.

## DOKUMEN REFERENSI (WAJIB DIBACA)
- `modules/administration/*` (lengkapi dulu)
- `MASTER_BLUEPRINT.md` (#21 Admin Panel)
- `SECURITY_STANDARD.md`, `UI_STANDARD.md`

## OUTPUT YANG HARUS DIBUAT
- Shell dashboard + navigasi berbasis permission.
- Manajemen user (lihat/suspend) sesuai RBAC.
- Audit log viewer.

## DEFINITION OF DONE
- Akses dashboard dibatasi role admin.
- Aksi sensitif tercatat di audit trail.

## LARANGAN
- ⛔ Memberi akses admin tanpa pemeriksaan RBAC.

## HAL YANG TIDAK BOLEH DIUBAH
- RBAC & audit trail standar.

## CHECKLIST SEBELUM SELESAI
- [ ] Navigasi mengikuti permission.
- [ ] Audit log tampil & benar.
- [ ] Jalankan `SECURITY_CHECKLIST.md` & `PRE_COMMIT.md`.
