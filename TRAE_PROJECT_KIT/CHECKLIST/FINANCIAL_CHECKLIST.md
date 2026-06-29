# CHECKLIST — FINANSIAL (Audit & Ledger)

- [ ] Setiap transaksi seimbang (Σ debit = Σ kredit).
- [ ] Ledger append-only & immutable (tanpa UPDATE/DELETE).
- [ ] Saldo diturunkan dari ledger; cache direkonsiliasi.
- [ ] Uang sebagai integer minor unit (bukan float/decimal).
- [ ] Idempotency pada setiap operasi nilai (no double-spend).
- [ ] Webhook gateway idempotent & terverifikasi server-side.
- [ ] Reversal = transaksi baru (bukan edit).
- [ ] Mission menerima nilai hanya via Revenue Sharing/Sponsor.
- [ ] Audit trail untuk freeze/withdraw/alokasi.
