# MODULE TEMPLATE — DAYA PLATFORM

> Template baku struktur dokumentasi **setiap modul**. Salin pola ini untuk setiap modul agar konsisten.
> Setiap folder modul di `/modules/<modul>/` wajib berisi 9 berkas berikut dengan struktur di bawah.

---

## STRUKTUR BERKAS WAJIB PER MODUL
```
modules/<modul>/
├── README.md             # Ikhtisar modul & indeks dokumen
├── BUSINESS_RULES.md     # Aturan bisnis berkode (BR-<MODUL>-NNN)
├── FLOW.md               # Alur proses + diagram Mermaid
├── DATABASE.md           # Skema, ERD, data dictionary, DDL
├── API.md                # Kontrak endpoint REST
├── UI.md                 # Layar, state, komponen (mobile-first)
├── DEVELOPMENT_NOTES.md  # Urutan implementasi & catatan teknis
├── TESTING_CHECKLIST.md  # Checklist uji fungsional, keamanan, finansial
└── DEPLOYMENT_NOTES.md   # Migrasi, cron, konfigurasi, smoke test
```

---

## KERANGKA TIAP BERKAS

### README.md
- Metadata (kode, versi, bounded context, status).
- Tujuan & ruang lingkup modul.
- Entity yang dimiliki & ringkasan domain.
- Referensi silang (Domain Model, Blueprint, Standards).
- Indeks 8 dokumen modul.

### BUSINESS_RULES.md
- Konvensi `BR-<MODUL>-NNN` (Kondisi/Pemicu → Aksi/Hasil).
- Aturan dikelompokkan per kategori (lifecycle, validasi, finansial, dst).
- Decision table untuk percabangan kompleks.

### FLOW.md
- Daftar flow utama.
- Tiap flow: diagram Mermaid (sequence/flow/state) + langkah + penanganan kegagalan.

### DATABASE.md
- Prinsip desain data modul.
- ERD (Mermaid), data dictionary (tabel kolom), DDL acuan.
- Strategi integritas & indexing. (Finansial: double-entry, append-only.)

### API.md
- Tabel endpoint (method, path, auth, deskripsi).
- Format request/response (amplop konsisten) & error.
- Aturan khusus (idempotency untuk finansial, RBAC).

### UI.md
- Daftar layar & user flow (mobile-first).
- State wajib (loading/empty/error/success).
- Komponen Bootstrap 5 & aturan tampilan (mis. konversi uang).

### DEVELOPMENT_NOTES.md
- Urutan implementasi internal modul.
- Tanggung jawab service & ketergantungan antar-modul.
- Jebakan umum & keputusan teknis penting.

### TESTING_CHECKLIST.md
- Checklist fungsional (per business rule).
- Checklist keamanan (OWASP, RBAC, validasi).
- Checklist finansial (idempotency, keseimbangan ledger) bila relevan.
- Edge cases.

### DEPLOYMENT_NOTES.md
- File migrasi SQL (urutan, via phpMyAdmin/File Manager).
- Cron yang diperlukan (cPanel/FastPanel).
- Konfigurasi environment & dependensi modul lain.
- Smoke test pasca-deploy & rollback.

---

## ATURAN PENGGUNAAN
- Status modul ditandai jelas di README: ✅ READY · 🟡 DOMAIN READY · 🔴 DRAFT.
- Modul 🔴/🟡 **dilarang** diimplementasi sebelum dilengkapi & disetujui.
- Setiap perubahan DB **wajib** memperbarui `DATABASE.md` lebih dulu.
- Konsistensi istilah mengikuti `DOMAIN_MODEL.md` (ubiquitous language).
