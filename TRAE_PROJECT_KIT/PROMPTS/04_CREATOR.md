# PHASE 4 — CREATOR
**Status dok:** 🟡 DOMAIN READY · **Bergantung:** Phase 2

> ⚠️ **GATE (🟡):** Domain model creator tersedia, tetapi BUSINESS_RULES/DATABASE/API/UI modul belum lengkap. Lengkapi mengikuti `MODULE_TEMPLATE.md` sebelum implementasi.

## TUJUAN
Mengelola identitas creator, onboarding/approval, tier, dan profil publik.

## DOKUMEN REFERENSI (WAJIB DIBACA)
- `modules/creator/README.md` (berisi domain model)
- `_domain_reference/CREATOR-DOMAIN.md`
- `MASTER_BLUEPRINT.md` (#6 Creator Economy)

## OUTPUT YANG HARUS DIBUAT
- Aplikasi & approval creator (lifecycle).
- Tier/leveling & profil publik (handle unik).
- Hak monetisasi (gerbang publish).

## DEFINITION OF DONE
- Hanya creator Active yang dapat publish.
- Lifecycle & tier sesuai domain model.

## LARANGAN
- ⛔ Membuat >1 profil creator per user.
- ⛔ Mengizinkan publish oleh creator non-Active.

## HAL YANG TIDAK BOLEH DIUBAH
- Creator sebagai role dari User (bukan identitas terpisah).

## CHECKLIST SEBELUM SELESAI
- [ ] Lifecycle creator teruji.
- [ ] Handle unik tervalidasi.
- [ ] Jalankan `PRE_COMMIT.md`.
