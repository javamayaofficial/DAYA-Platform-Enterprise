# DAYA PLATFORM — WALLET BUSINESS RULES

> Modul pilot **Wallet** (Financial Core). Dokumen ini memformalkan seluruh aturan bisnis Wallet sebagai *single source of truth* perilaku finansial.
> Patuh penuh pada **Audit & Ledger Principles (#34)**.

## METADATA

| Atribut | Nilai |
|---|---|
| Kode Dokumen | `DAYA-09-WALLET-01-BUSINESS-RULES` |
| Versi | `1.0.0` |
| Modul | Wallet (Pilot) |
| Bounded Context | `BC-FIN` |
| Induk | `DAYA-01.04-WALLET-DOMAIN` |
| Status | `🟢 Active — Core` |

---

## 1. KONVENSI PENULISAN ATURAN

Setiap aturan memiliki ID unik `BR-WALLET-NNN`, dengan struktur **Kondisi/Pemicu → Aksi/Hasil**. Aturan bersifat normatif (mengikat) kecuali ditandai *(rekomendasi)*.

---

## 2. WALLET LIFECYCLE & PROVISIONING

| ID | Aturan | Kondisi/Pemicu | Aksi/Hasil |
|---|---|---|---|
| BR-WALLET-001 | Setiap `User` memiliki tepat satu Wallet. | User dibuat & terverifikasi. | Sistem otomatis membuat Wallet berstatus `Active`. |
| BR-WALLET-002 | Wallet tidak dapat dihapus. | Permintaan hapus. | Ditolak; Wallet hanya dapat `Frozen`/`Closed`. |
| BR-WALLET-003 | Wallet `Frozen` tidak boleh memutasi nilai. | Wallet dibekukan Admin. | Semua top-up/spend/withdraw ditolak; saldo tetap utuh. |
| BR-WALLET-004 | Wallet `Closed` bersifat final. | Akun dinonaktifkan permanen. | Saldo wajib nol sebelum ditutup; tidak ada mutasi setelahnya. |

## 3. INTEGRITAS SALDO

| ID | Aturan | Kondisi/Pemicu | Aksi/Hasil |
|---|---|---|---|
| BR-WALLET-010 | Saldo adalah **turunan** dari Ledger. | Setiap pembacaan saldo. | Saldo = agregasi entri Ledger; cache saldo hanya untuk performa & wajib dapat direkonsiliasi. |
| BR-WALLET-011 | Saldo tidak boleh negatif. | Operasi yang akan membuat saldo < 0. | Operasi ditolak (kecuali aturan kredit khusus terkonfigurasi). |
| BR-WALLET-012 | Tidak ada mutasi saldo tanpa Transaction. | Upaya mengubah saldo langsung. | Dilarang; setiap perubahan harus melewati Transaction → Ledger. |
| BR-WALLET-013 | Mata uang Wallet konsisten. | Operasi lintas mata uang. | Ditolak pada V1 (single currency); konversi adalah fitur masa depan. |

## 4. CREDIT MANAGEMENT

| ID | Aturan | Kondisi/Pemicu | Aksi/Hasil |
|---|---|---|---|
| BR-WALLET-020 | Penambahan/pengurangan Credit wajib menghasilkan entri Ledger. | Top-up/spend credit. | Posting double-entry dibuat. |
| BR-WALLET-021 | Credit dapat memiliki masa berlaku. | Credit memiliki `expires_at`. | Setelah kedaluwarsa, sisa credit hangus & dibukukan sebagai entri ekspirasi. |
| BR-WALLET-022 | Konsumsi credit mengikuti urutan kedaluwarsa terdekat *(FIFO by expiry)*. | Spend menggunakan credit. | Credit yang paling cepat kedaluwarsa dipakai lebih dulu. |
| BR-WALLET-023 | Credit hangus tidak dapat dikembalikan. | Credit `Expired`. | Tidak dapat dipulihkan kecuali kebijakan khusus Admin + jejak audit. |

## 5. TRANSACTION RULES

| ID | Aturan | Kondisi/Pemicu | Aksi/Hasil |
|---|---|---|---|
| BR-WALLET-030 | Setiap Transaction wajib **idempotent**. | Permintaan dengan `idempotency_key` sama. | Hanya diproses sekali; permintaan ulang mengembalikan hasil yang sama. |
| BR-WALLET-031 | Transaction wajib **seimbang** saat diposting. | Posting ke Ledger. | Σ debit = Σ kredit; bila tidak, posting gagal & di-rollback. |
| BR-WALLET-032 | Transaction final **tidak diubah**. | Permintaan edit transaksi selesai. | Ditolak; koreksi hanya via Transaction reversal (compensating). |
| BR-WALLET-033 | Reversal merujuk transaksi asal. | Koreksi diperlukan. | Dibuat Transaction baru bertipe `Reversal` yang menunjuk transaksi asal. |
| BR-WALLET-034 | Transaction `Pending` memiliki batas waktu. | Pending melewati TTL. | Otomatis ditandai `Failed` & tidak memengaruhi saldo. |

## 6. LEDGER RULES

| ID | Aturan | Kondisi/Pemicu | Aksi/Hasil |
|---|---|---|---|
| BR-WALLET-040 | Ledger bersifat **append-only**. | Setiap pembukuan. | Entri hanya ditambah; tidak ada UPDATE/DELETE. |
| BR-WALLET-041 | Entri Ledger **immutable**. | Upaya mengubah entri. | Dilarang untuk semua peran, termasuk Super Admin. |
| BR-WALLET-042 | Setiap entri memiliki sisi & akun jelas. | Posting entri. | Entri mencantumkan akun, tipe (debit/kredit), jumlah, transaksi. |
| BR-WALLET-043 | Akun sistem dipakai untuk pihak non-user. | Alokasi platform/mission/gateway. | Memakai akun sistem (platform, mission_fund, gateway_clearing). |

## 7. TOP-UP RULES

| ID | Aturan | Kondisi/Pemicu | Aksi/Hasil |
|---|---|---|---|
| BR-WALLET-050 | Top-up hanya menambah nilai bila pembayaran sukses. | Webhook `Payment` sukses & terverifikasi. | Nilai ditambahkan via Transaction `Topup`. |
| BR-WALLET-051 | Data dari klien tidak dipercaya untuk menambah nilai. | Klaim sukses dari front-end. | Diabaikan; hanya verifikasi gateway yang sah. |
| BR-WALLET-052 | Top-up memiliki batas minimum/maksimum. | Jumlah di luar batas. | Ditolak sesuai konfigurasi (#33). |

## 8. SPEND RULES

| ID | Aturan | Kondisi/Pemicu | Aksi/Hasil |
|---|---|---|---|
| BR-WALLET-060 | Spend memerlukan saldo/credit cukup. | Saldo < jumlah. | Ditolak (BR-WALLET-011). |
| BR-WALLET-061 | Spend pembelian memicu Revenue Sharing. | Pembelian konten/membership. | Setelah debit, `Revenue Sharing` mengalokasikan ke pihak terkait. |
| BR-WALLET-062 | Spend pada Wallet `Frozen` ditolak. | Wallet dibekukan. | Operasi gagal (BR-WALLET-003). |

## 9. WITHDRAW INTERACTION

| ID | Aturan | Kondisi/Pemicu | Aksi/Hasil |
|---|---|---|---|
| BR-WALLET-070 | Withdraw menahan dana sejak diminta. | Permintaan withdraw. | Dana di-*hold* (mengurangi saldo tersedia) menunggu persetujuan. |
| BR-WALLET-071 | Withdraw ditolak mengembalikan hold. | Withdraw `Rejected/Failed`. | Hold dilepas; saldo tersedia pulih via entri Ledger. |
| BR-WALLET-072 | Withdraw selesai membukukan pengeluaran final. | Withdraw `Completed`. | Entri Ledger pengeluaran dibukukan permanen. |

> Detail proses Withdraw eksternal berada di Payment Domain (#10); di sini hanya interaksi terhadap saldo.

## 10. FREEZE & RISK CONTROL

| ID | Aturan | Kondisi/Pemicu | Aksi/Hasil |
|---|---|---|---|
| BR-WALLET-080 | Hanya Admin/Super Admin dapat membekukan Wallet. | Indikasi fraud/sengketa. | Wallet → `Frozen`; tercatat di audit log. |
| BR-WALLET-081 | Pembekuan tidak menghapus saldo. | Wallet `Frozen`. | Saldo tetap; hanya mutasi yang dihentikan. |
| BR-WALLET-082 | Pembekuan & pencabutannya wajib beralasan & tercatat. | Aksi freeze/unfreeze. | Alasan + aktor disimpan di audit trail. |

## 11. IDEMPOTENCY & CONCURRENCY

| ID | Aturan | Kondisi/Pemicu | Aksi/Hasil |
|---|---|---|---|
| BR-WALLET-090 | Operasi nilai memakai kunci idempotensi. | Setiap request mutasi. | Duplikat dikenali & tidak diproses ganda. |
| BR-WALLET-091 | Akses saldo concurrent harus aman. | Dua operasi simultan. | Dikunci pada level transaksi DB agar konsisten (no double-spend). |

## 12. RECONCILIATION

| ID | Aturan | Kondisi/Pemicu | Aksi/Hasil |
|---|---|---|---|
| BR-WALLET-100 | Cache saldo wajib cocok dengan Ledger. | Rekonsiliasi terjadwal. | Bila selisih, Ledger menang; cache dikoreksi & dicatat. |
| BR-WALLET-101 | Ketidakcocokan dilaporkan. | Selisih ditemukan. | Notifikasi ke Admin & masuk laporan audit. |

## 13. DECISION TABLE — Operasi Spend

| Wallet Status | Saldo Cukup? | Idempotency Baru? | Hasil |
|---|:---:|:---:|---|
| Active | Ya | Ya | Spend diproses, Ledger diposting, Revenue Sharing dipicu |
| Active | Tidak | — | Ditolak: saldo tidak cukup |
| Active | Ya | Tidak (duplikat) | Kembalikan hasil sebelumnya (tanpa proses ulang) |
| Frozen | — | — | Ditolak: wallet dibekukan |
| Closed | — | — | Ditolak: wallet tertutup |

---

## CHANGE LOG
| Versi | Tanggal | Perubahan |
|---|---|---|
| 1.0.0 | — | Penerbitan awal Wallet Business Rules (BR-WALLET-001..101). |

**— Akhir Wallet Business Rules —**
