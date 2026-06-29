# PHASE 14 — DEPLOYMENT
**Status dok:** ✅ Standar lengkap · **Bergantung:** Semua phase

## TUJUAN
Merilis aplikasi ke cPanel/FastPanel secara aman & teruji, tanpa CI/CD modern.

## DOKUMEN REFERENSI (WAJIB DIBACA)
- `PROJECT_CONSTITUTION.md` (§14 DoD, §12 Versioning, §13 Change Mgmt)
- Setiap `modules/*/DEPLOYMENT_NOTES.md`
- `DATABASE_STANDARD.md` (migrasi), `SECURITY_STANDARD.md`

## OUTPUT YANG HARUS DIBUAT
- Penerapan migrasi DB semua modul (via phpMyAdmin/File Manager, urut).
- Konfigurasi environment di luar web root.
- Cron terpasang (rekonsiliasi, ekspirasi credit, notifikasi).
- Backup pra-rilis + tag versi (Semantic Versioning) + changelog.

## DEFINITION OF DONE
- Smoke test lolos di lingkungan target.
- Backup tersedia & rollback teruji.
- HTTPS aktif; rahasia aman.

## LARANGAN
- ⛔ Deploy tanpa backup.
- ⛔ Menaruh rahasia di repo/web root.
- ⛔ Mengubah ledger secara manual saat rollback.

## HAL YANG TIDAK BOLEH DIUBAH
- Constraint shared hosting (tanpa Docker/SSH/Terminal).
- Versioning & changelog wajib.

## CHECKLIST SEBELUM SELESAI
- [ ] Semua migrasi diterapkan & terverifikasi.
- [ ] Cron berjalan.
- [ ] Smoke test lolos.
- [ ] `CHECKLIST/PRE_RELEASE.md` tuntas.
