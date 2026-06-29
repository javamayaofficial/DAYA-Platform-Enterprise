# PHASE 2 — AUTHENTICATION
**Status dok:** 🔴 DRAFT · **Bergantung:** Phase 1

> ⚠️ **GATE DOKUMENTASI (🔴 DRAFT):** Dokumen modul ini di Developer Pack masih scaffold. **JANGAN menulis kode** sebelum dokumen modul (BUSINESS_RULES/FLOW/DATABASE/API/UI/dst) dilengkapi mengikuti `MODULE_TEMPLATE.md` dan disetujui. Jika belum lengkap → **berhenti & minta pelengkapan.**

## TUJUAN
Membangun identitas pengguna & kontrol akses (RBAC) sebagai prasyarat seluruh modul.

## DOKUMEN REFERENSI (WAJIB DIBACA)
- `modules/authentication/*` (lengkapi dulu)
- `DOMAIN_MODEL.md` (User, sec. 6.1)
- `SECURITY_STANDARD.md`, `MASTER_BLUEPRINT.md` (#5 RBAC)

## OUTPUT YANG HARUS DIBUAT
- Registrasi, verifikasi, login, logout.
- Manajemen sesi/token aman.
- RBAC: role, permission, middleware otorisasi.
- Account lifecycle (pending → active → suspended → deactivated).

## DEFINITION OF DONE
- Alur auth lengkap & aman (hash password, CSRF, sesi aman).
- RBAC menegakkan least privilege.
- Sesuai business rules modul authentication.

## LARANGAN
- ⛔ Menyimpan password plaintext.
- ⛔ Mempercayai role dari sisi klien.

## HAL YANG TIDAK BOLEH DIUBAH
- Standar keamanan (SECURITY_STANDARD).
- Model User sebagai base identity (DOMAIN_MODEL).

## CHECKLIST SEBELUM SELESAI
- [ ] Password di-hash (`password_hash`).
- [ ] CSRF & sesi aman.
- [ ] RBAC teruji (akses ditolak bila tak berwenang).
- [ ] Audit log untuk login/perubahan role.
- [ ] Jalankan `CHECKLIST/SECURITY_CHECKLIST.md` & `PRE_COMMIT.md`.
