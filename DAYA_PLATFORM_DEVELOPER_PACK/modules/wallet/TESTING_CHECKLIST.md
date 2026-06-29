# WALLET MODULE — TESTING CHECKLIST

> Uji wajib sebelum modul dianggap selesai. Centang semua.

## FUNGSIONAL (per Business Rule)
- [ ] Wallet otomatis dibuat saat user terverifikasi (BR-001).
- [ ] Wallet tidak dapat dihapus; hanya frozen/closed (BR-002).
- [ ] Top-up hanya menambah saldo bila pembayaran terverifikasi (BR-050/051).
- [ ] Spend gagal bila saldo tidak cukup (BR-011/060).
- [ ] Spend pada wallet frozen ditolak (BR-062).
- [ ] Credit dikonsumsi FIFO by expiry (BR-022).
- [ ] Credit kedaluwarsa hangus & terbukukan (BR-021/023).

## FINANSIAL / LEDGER
- [ ] Setiap transaksi seimbang (Σ debit = Σ kredit) (BR-031).
- [ ] Ledger append-only: tidak ada UPDATE/DELETE (BR-040/041).
- [ ] Saldo selalu sama dengan agregasi ledger (BR-010).
- [ ] Reversal membuat transaksi baru yang menunjuk asal (BR-033).
- [ ] Rekonsiliasi mengoreksi cache ke ledger & melaporkan selisih (BR-100/101).

## IDEMPOTENCY & CONCURRENCY
- [ ] Request ganda dengan key sama tidak memproses dobel (BR-090).
- [ ] Dua spend simultan tidak menyebabkan double-spend (BR-091).
- [ ] Webhook gateway ganda hanya berefek sekali.

## KEAMANAN
- [ ] Semua input tervalidasi server-side.
- [ ] Semua kueri memakai prepared statements.
- [ ] Hanya Admin yang dapat freeze/unfreeze (BR-080) + alasan tercatat (BR-082).
- [ ] Akses transaksi dibatasi pemilik / Admin audit.

## EDGE CASES
- [ ] Pending melebihi TTL → Failed, saldo tak terpengaruh (BR-034).
- [ ] Withdraw ditolak melepas hold & memulihkan saldo (BR-071).
- [ ] Saldo nol & operasi nol ditangani benar.
