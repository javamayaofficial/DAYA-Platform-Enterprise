# TRAE PROJECT KIT v1.0 — README_FIRST

> **Panduan eksekusi bertahap untuk TRAE AI.** Dipakai **bersama** `DAYA_PLATFORM_DEVELOPER_PACK_v1.0` (referensi spesifikasi).
> Kit ini menjawab "**apa yang dikerjakan, urutannya, dan kapan dianggap selesai**" — bukan menggantikan dokumentasi.

---

## CARA PAKAI
1. Pastikan **Developer Pack v1.0** tersedia (sumber spesifikasi & standar).
2. Baca **IMPLEMENTATION_SEQUENCE.md** → pahami 14 phase & ketergantungannya.
3. Kerjakan **satu phase pada satu waktu**, urut. Jangan lompat.
4. Untuk tiap phase, buka prompt-nya di `PROMPTS/` dan **ikuti persis**.
5. Tandai progres di **TASKLIST.md**.
6. Sebelum selesai/commit/rilis, jalankan checklist di `CHECKLIST/`.

---

## ATURAN MUTLAK
- ⛔ **Jangan menulis kode** untuk modul yang dokumentasinya masih 🔴 DRAFT. **Berhenti & minta pelengkapan dokumen** (mengikuti `MODULE_TEMPLATE.md`) lebih dulu.
- ⛔ **Jangan membuat asumsi** di luar Developer Pack. Bila tidak tercakup → berhenti & tanya.
- ✅ Selalu patuh **PROJECT_CONSTITUTION** & seluruh **STANDARD** (Coding/Folder/DB/API/Security/UI).
- ✅ Setiap fitur wajib: **validasi, logging, audit trail, security**.
- ✅ Target deployment **cPanel/FastPanel** — tanpa Docker/NodeJS/SSH/Terminal.

---

## LEGENDA STATUS DOKUMENTASI
| Simbol | Arti | Boleh dikodekan? |
|---|---|:---:|
| ✅ READY | Dokumen modul lengkap | Ya |
| 🟡 DOMAIN READY | Hanya domain model tersedia | Tidak — lengkapi dulu |
| 🔴 DRAFT | Belum dirancang | Tidak — lengkapi dulu |

> Saat ini hanya **Phase 7 (Wallet)** ber-status ✅. Phase 1 (Bootstrap) memakai standar yang sudah lengkap. Sisanya wajib dilengkapi dokumennya sebelum diimplementasi.
