# IMPLEMENTATION GUIDE — DAYA PLATFORM

> Dokumen acuan implementasi menyeluruh. **Wajib dibaca setelah README_FIRST.md.**
> Menggabungkan inti PROJECT_CONSTITUTION, MASTER_BLUEPRINT, dan seluruh Standar menjadi panduan operasional developer.

---

## 1. RINGKASAN PROYEK
DAYA Platform adalah **Mission-Driven Creator Economy Platform** berbasis PHP Native + MySQL yang akan berevolusi menjadi SaaS Enterprise. Setiap transaksi menghasilkan nilai ekonomi bagi creator sekaligus mengalirkan sebagian nilai ke misi sosial secara transparan & auditable.

## 2. TUJUAN APLIKASI
- Memberi creator kanal monetisasi yang adil & berkelanjutan.
- Menjamin integritas finansial melalui ledger double-entry yang immutable.
- Mengalirkan & melaporkan dana misi secara transparan.
- Berjalan di shared hosting (cPanel/FastPanel) tanpa tooling berat.

## 3. TECH STACK
| Lapisan | Teknologi | Catatan |
|---|---|---|
| Backend | PHP Native | Tanpa framework berat; modular. |
| Database | MySQL (InnoDB) | Transaksi DB wajib untuk modul finansial. |
| UI | Bootstrap 5 + Vanilla JS | Mobile-first, tanpa build tool. |
| Deployment | cPanel / FastPanel | File Manager + phpMyAdmin + cron. |
| VCS | GitHub | Source of Truth. |
| Terlarang | Docker, NodeJS, SSH, Terminal | Bukan prasyarat produksi. |

## 4. ARSITEKTUR
**Modular Monolith** dengan pemisahan tegas: Router → Middleware (Auth/RBAC/CSRF/RateLimit) → Controller (thin) → Service (business logic) → Repository (data access) → MySQL. Setiap modul self-contained. Detail di `PROJECT_CONSTITUTION.md` §6 dan `FOLDER_STRUCTURE.md`.

## 5. STRUKTUR FOLDER
Lihat **FOLDER_STRUCTURE.md** (lengkap). Ringkas: hanya `public/` yang terekspos web; kode aplikasi di `app/modules/<modul>/{controllers,services,models,views}`; `storage/` & konfigurasi di luar web root.

## 6. URUTAN IMPLEMENTASI
1. **Fondasi teknis:** Router, Middleware, Config loader, Database connection (PDO), Logging, Error handler.
2. **authentication** (setelah dokumen lengkap) — identitas & RBAC adalah prasyarat semua modul.
3. **wallet** — ✅ siap diimplementasi (financial core, sudah lengkap). Gunakan sebagai **reference implementation**.
4. **content** & **part** (setelah lengkap).
5. **payment** → **affiliate** → **notification**.
6. **analytics** → **administration**.

> Modul finansial (wallet, payment, affiliate/revenue) mengikuti **Audit & Ledger Principles**: double-entry, append-only, immutable, idempotent.

## 7. CODING RULES
Ringkas dari **CODING_STANDARD.md**: PascalCase untuk class, camelCase untuk method/variabel, snake_case untuk tabel/kolom, kebab-case untuk URL. Thin controller, rich service. DRY, clean code, single responsibility. Tanpa magic number (gunakan konfigurasi).

## 8. SECURITY RULES
Ringkas dari **SECURITY_STANDARD.md**: validasi & sanitasi server-side wajib; PDO prepared statements (dilarang konkatenasi SQL); CSRF token pada form mutasi; `password_hash()`; RBAC least privilege; rahasia di luar web root; audit logging; mitigasi OWASP Top 10.

## 9. DATABASE RULES
Ringkas dari **DATABASE_STANDARD.md**: InnoDB; uang sebagai integer minor unit (BIGINT); ledger append-only & immutable; FK & transaksi DB untuk integritas; migrasi via file SQL bernomor (File Manager/phpMyAdmin); charset utf8mb4.

## 10. API RULES
Ringkas dari **API_STANDARD.md**: REST, prefix `/api/v1`, resource jamak, verb HTTP semantik, amplop respons konsisten, format error baku, auth via token, idempotency-key untuk endpoint finansial, pagination & rate limiting.

## 11. UI RULES
Ringkas dari **UI_STANDARD.md**: mobile-first, Bootstrap 5, aksesibilitas, status loading/empty/error eksplisit, konversi tampilan uang dari minor unit, SEO-friendly markup.

## 12. NAMING CONVENTION
| Elemen | Konvensi |
|---|---|
| Class | PascalCase |
| Method/Variabel | camelCase |
| Konstanta | UPPER_SNAKE_CASE |
| Tabel/Kolom | snake_case |
| URL/Route | kebab-case |
| File dokumen | UPPER_SNAKE_CASE.md (di pack), `DAYA-NN-CODE-*.md` (di repo docs) |

## 13. GIT WORKFLOW
- Branch: `main` (stabil) ← `develop` ← `feature/<modul>-<ringkas>`.
- Satu PR per fitur; commit message jelas & bermakna.
- Tidak ada commit langsung ke `main`.
- Setiap PR merujuk Business Rules / dokumen terkait.
- Rahasia tidak pernah masuk repo.

## 14. DEPLOYMENT WORKFLOW
1. Tag rilis (Semantic Versioning) di GitHub.
2. Upload via cPanel/FastPanel File Manager (atau Git deploy bila tersedia).
3. Terapkan migrasi SQL via phpMyAdmin (file bernomor).
4. Set konfigurasi environment di luar web root.
5. Atur cron (rekonsiliasi, ekspirasi, notifikasi).
6. Verifikasi smoke test + backup pra-rilis. Detail di tiap `DEPLOYMENT_NOTES.md`.

## 15. CHECKLIST SEBELUM COMMIT
- [ ] Mengikuti CODING_STANDARD & FOLDER_STRUCTURE.
- [ ] Input tervalidasi & tersanitasi (server-side).
- [ ] Memakai PDO prepared statements.
- [ ] Logging & audit trail untuk aksi penting.
- [ ] Tidak ada rahasia/kredensial di kode.
- [ ] Tidak ada hardcoded value untuk parameter konfigurabel.
- [ ] Sesuai Business Rules modul.
- [ ] Dokumentasi terkait diperbarui (terutama bila DB berubah).

## 16. CHECKLIST SEBELUM RELEASE
- [ ] Semua kriteria Definition of Done terpenuhi (Constitution §14).
- [ ] Testing modul lolos (lihat TESTING_CHECKLIST modul).
- [ ] Pemeriksaan keamanan dasar (OWASP) lolos.
- [ ] Modul finansial: rekonsiliasi seimbang, idempotency teruji.
- [ ] Responsive di mobile.
- [ ] Migrasi DB teruji & terdokumentasi.
- [ ] Changelog & versioning diperbarui.
- [ ] Backup pra-rilis tersedia.

---

> **Prinsip penutup:** Bila ragu, kembali ke PROJECT_CONSTITUTION. Bila tidak tercakup, **berhenti & minta klarifikasi** — jangan berimprovisasi.
