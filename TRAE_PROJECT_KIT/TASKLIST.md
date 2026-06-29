# TASKLIST — Progres Implementasi DAYA Platform

> Tandai saat selesai. Jangan menandai selesai sebelum Definition of Done & checklist phase terpenuhi.

## PHASE 1 — PROJECT BOOTSTRAP
- [ ] Struktur folder sesuai FOLDER_STRUCTURE
- [ ] Front controller + Router
- [ ] Middleware pipeline (Auth/RBAC/CSRF/RateLimit) — kerangka
- [ ] Koneksi DB (PDO) + config loader (di luar web root)
- [ ] Logging & error handler global
- [ ] Layout dasar Bootstrap 5 (mobile-first)

## PHASE 2 — AUTHENTICATION  _(lengkapi dok dulu)_
- [ ] Dokumen modul authentication lengkap & disetujui
- [ ] Registrasi, verifikasi, login, logout
- [ ] RBAC (role & permission)
- [ ] Session/token aman

## PHASE 3 — ADMIN DASHBOARD  _(lengkapi dok dulu)_
- [ ] Dokumen modul administration lengkap
- [ ] Shell dashboard + navigasi RBAC
- [ ] Audit log viewer

## PHASE 4 — CREATOR  _(lengkapi dok dulu)_
- [ ] Dokumen modul creator lengkap
- [ ] Onboarding & approval creator
- [ ] Tier & profil publik

## PHASE 5 — CONTENT  _(lengkapi dok dulu)_
- [ ] Dokumen modul content lengkap
- [ ] CRUD content + moderasi
- [ ] Taksonomi content type

## PHASE 6 — CONTENT PART  _(lengkapi dok dulu)_
- [ ] Dokumen modul part lengkap
- [ ] Part terurut + drip release

## PHASE 7 — WALLET  ✅ siap
- [ ] Migrasi DB wallet (7 file) + seed akun sistem
- [ ] LedgerService (double-entry, append-only)
- [ ] WalletService (provisioning, topup, spend)
- [ ] Idempotency guard
- [ ] ReconciliationService + cron
- [ ] API & UI wallet

## PHASE 8 — PAYMENT DUITKU  _(lengkapi dok dulu)_
- [ ] Dokumen modul payment lengkap
- [ ] Integrasi Duitku (ACL) + webhook idempotent
- [ ] Topup & withdraw → wallet

## PHASE 9 — AFFILIATE  _(lengkapi dok dulu)_
- [ ] Dokumen modul affiliate lengkap
- [ ] Referral & atribusi + anti-fraud
- [ ] Komisi via Revenue Sharing

## PHASE 10 — NOTIFICATION  _(lengkapi dok dulu)_
- [ ] Dokumen modul notification lengkap
- [ ] Multi-channel + template + queue/cron

## PHASE 11 — ANALYTICS  _(lengkapi dok dulu)_
- [ ] Dokumen modul analytics lengkap
- [ ] Event taxonomy + dashboard

## PHASE 12 — SPONSOR  _(lengkapi dok dulu)_
- [ ] Dokumen modul sponsor lengkap
- [ ] Sponsor & CSR program

## PHASE 13 — FOUNDATION  _(lengkapi dok dulu)_
- [ ] Dokumen modul foundation lengkap
- [ ] Mission fund + campaign + transparansi

## PHASE 14 — DEPLOYMENT
- [ ] Migrasi semua modul teruji (phpMyAdmin)
- [ ] Cron terpasang (rekonsiliasi, ekspirasi, notifikasi)
- [ ] Smoke test + backup + changelog/version
