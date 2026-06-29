# README_FIRST — DIBACA PERTAMA OLEH TRAE AI

> **STOP. Baca dokumen ini sepenuhnya sebelum melakukan apa pun.**
> Paket ini adalah **satu-satunya** referensi implementasi DAYA Platform. Jangan gunakan asumsi di luar paket ini.

---

## 1. URUTAN MEMBACA WAJIB

1. **README_FIRST.md** (dokumen ini)
2. **IMPLEMENTATION_GUIDE.md** — peta jalan implementasi menyeluruh
3. **PROJECT_CONSTITUTION.md** — hukum tertinggi proyek
4. **MASTER_BLUEPRINT.md** — peta seluruh dokumentasi
5. **DOMAIN_MODEL.md** — model bisnis (ubiquitous language)
6. **Standar:** CODING_STANDARD · FOLDER_STRUCTURE · DATABASE_STANDARD · API_STANDARD · SECURITY_STANDARD · UI_STANDARD
7. **MODULE_TEMPLATE.md** — struktur baku setiap modul
8. **modules/** — dokumentasi per modul (mulai dari `wallet` sebagai referensi)

---

## 2. ATURAN MUTLAK UNTUK TRAE AI

- ✅ **Baca IMPLEMENTATION_GUIDE.md lebih dulu** sebelum menulis kode apa pun.
- ⛔ **Jangan membuat asumsi** di luar dokumentasi. Bila ada yang tidak jelas/tidak ada, **berhenti dan minta klarifikasi** — jangan menebak.
- ✅ **Selalu ikuti PROJECT_CONSTITUTION.** Bila ada konflik antar dokumen, Constitution yang berlaku.
- ✅ **Selalu ikuti CODING_STANDARD & FOLDER_STRUCTURE.** Tidak ada pengecualian.
- ✅ **Semua implementasi harus modular** sesuai struktur folder.
- ⛔ **Jangan mengubah Business Rules** tanpa persetujuan tertulis.
- ⛔ **Jangan mengubah Database** tanpa memperbarui `DATABASE.md` modul terkait terlebih dahulu.
- ✅ **Semua fitur wajib** memiliki: **validasi input**, **logging**, **audit trail**, dan **security** (lihat SECURITY_STANDARD).
- ⛔ **Tanpa Docker, NodeJS, SSH, atau Terminal** sebagai prasyarat. Target deployment: **cPanel / FastPanel** (shared hosting).
- ✅ **Stack wajib:** PHP Native + MySQL (InnoDB) + Bootstrap 5 + Vanilla JS.

---

## 3. STATUS DOKUMENTASI (PENTING)

Paket ini **tidak** semua modulnya siap diimplementasi. Patuhi status berikut:

| Modul | Status | Boleh Diimplementasi? |
|---|---|:---:|
| **wallet** | ✅ IMPLEMENTATION READY (pilot lengkap) | **YA** |
| creator | 🟡 DOMAIN READY (domain model ada, detail belum) | Tidak — lengkapi dulu |
| content | 🟡 DOMAIN READY | Tidak — lengkapi dulu |
| authentication | 🔴 DRAFT (scaffold) | Tidak |
| part | 🔴 DRAFT (scaffold) | Tidak |
| payment | 🔴 DRAFT (scaffold) | Tidak |
| affiliate | 🔴 DRAFT (scaffold) | Tidak |
| notification | 🔴 DRAFT (scaffold) | Tidak |
| analytics | 🔴 DRAFT (scaffold) | Tidak |
| administration | 🔴 DRAFT (scaffold) | Tidak |

> **Aturan status:** Modul ber-status 🟡/🔴 **DILARANG** diimplementasi sampai dokumennya dilengkapi mengikuti `MODULE_TEMPLATE.md` dan disetujui. **Mulailah dari modul `wallet`** sebagai pola acuan; modul lain mengikuti pola yang sama setelah dokumennya lengkap.

---

## 4. ALUR KERJA SINGKAT

```
Baca paket → Konfirmasi pemahaman → Implementasi modul READY (wallet)
   → Review terhadap Business Rules & Standards → Commit (lihat checklist)
   → Modul berikutnya hanya setelah dokumennya lengkap & disetujui
```

> Jika sebuah kebutuhan tidak tercakup paket ini: **JANGAN berimprovisasi.** Catat sebagai pertanyaan dan tunggu pembaruan dokumentasi.

**— Selesai. Lanjut ke IMPLEMENTATION_GUIDE.md —**
