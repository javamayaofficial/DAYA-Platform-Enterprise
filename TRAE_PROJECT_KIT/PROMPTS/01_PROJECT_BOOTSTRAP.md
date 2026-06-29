# PHASE 1 — PROJECT BOOTSTRAP
**Status dok:** ✅ Standar lengkap · **Bergantung:** —

## TUJUAN
Menyiapkan fondasi teknis aplikasi (kerangka modular monolith) sesuai standar, sehingga semua modul berikutnya dibangun di atas pondasi konsisten.

## DOKUMEN REFERENSI (WAJIB DIBACA)
- `PROJECT_CONSTITUTION.md` (§6 Arsitektur, §8 Coding)
- `FOLDER_STRUCTURE.md`
- `CODING_STANDARD.md`, `SECURITY_STANDARD.md`, `DATABASE_STANDARD.md`, `API_STANDARD.md`, `UI_STANDARD.md`
- `IMPLEMENTATION_GUIDE.md`

## OUTPUT YANG HARUS DIBUAT
- Struktur folder sesuai `FOLDER_STRUCTURE.md` (public/ sebagai satu-satunya web root).
- Front controller + Router.
- Kerangka middleware: Auth, RBAC, CSRF, RateLimit.
- Config loader (rahasia di luar web root) + koneksi DB via PDO.
- Logging & global error handler.
- Layout dasar Bootstrap 5 (mobile-first) + struktur asset.

## DEFINITION OF DONE
- Aplikasi dapat melayani route uji melalui front controller.
- Standar folder & penamaan dipatuhi.
- Koneksi DB (PDO prepared) berfungsi; konfigurasi aman.
- Logging & error handler aktif.

## LARANGAN
- ⛔ Tanpa Docker/NodeJS/SSH/Terminal sebagai prasyarat.
- ⛔ Tanpa framework berat; PHP Native modular.
- ⛔ Tidak menaruh kode aplikasi di dalam `public/`.

## HAL YANG TIDAK BOLEH DIUBAH
- Struktur folder & konvensi penamaan (FOLDER_STRUCTURE, CODING_STANDARD).
- Prinsip arsitektur (thin controller, rich service).

## CHECKLIST SEBELUM SELESAI
- [ ] Struktur folder sesuai standar.
- [ ] Router + middleware pipeline berjalan.
- [ ] PDO + config aman (di luar web root).
- [ ] Logging & error handler aktif.
- [ ] Layout Bootstrap 5 responsif.
- [ ] Jalankan `CHECKLIST/PRE_COMMIT.md`.
