# PHASE 5 — CONTENT
**Status dok:** 🟡 DOMAIN READY · **Bergantung:** Phase 4

> ⚠️ **GATE (🟡):** Domain model content tersedia; detail modul belum lengkap. Lengkapi via `MODULE_TEMPLATE.md` dulu.

## TUJUAN
Mengelola siklus hidup karya, taksonomi, dan model akses/harga.

## DOKUMEN REFERENSI (WAJIB DIBACA)
- `modules/content/README.md` (berisi domain model)
- `_domain_reference/CONTENT-DOMAIN.md`
- `MASTER_BLUEPRINT.md` (#11 Content Engine)

## OUTPUT YANG HARUS DIBUAT
- CRUD content + moderasi (Draft→InReview→Published→...).
- Content Type (taksonomi).
- Model akses (gratis/berbayar/membership) & harga.

## DEFINITION OF DONE
- Hanya content Published yang dapat diakses.
- Perubahan harga tidak berlaku surut.

## LARANGAN
- ⛔ Menyajikan content non-Published ke publik.
- ⛔ Mengubah harga pembelian yang sudah terjadi.

## HAL YANG TIDAK BOLEH DIUBAH
- Aturan akses & non-retroaktif harga (domain content).

## CHECKLIST SEBELUM SELESAI
- [ ] Moderasi sebelum publish berjalan.
- [ ] Slug unik & SEO-friendly.
- [ ] Jalankan `PRE_COMMIT.md`.
