# DAYA PLATFORM — WALLET FLOW

> Modul pilot **Wallet**. Dokumen ini menjabarkan alur proses (business & process flow) operasi Wallet, termasuk penanganan kegagalan & idempotency.
> Mengacu pada **Wallet Business Rules (`DAYA-09-WALLET-01`)**.

## METADATA

| Atribut | Nilai |
|---|---|
| Kode Dokumen | `DAYA-09-WALLET-02-FLOW` |
| Versi | `1.0.0` |
| Modul | Wallet (Pilot) |
| Status | `🟢 Active — Core` |

---

## 1. RINGKASAN ALUR

| Flow | Pemicu | Hasil Akhir |
|---|---|---|
| F1 — Wallet Provisioning | User terverifikasi | Wallet `Active` dibuat |
| F2 — Top-up | Pembayaran sukses | Saldo bertambah |
| F3 — Spend / Purchase | Pembelian | Saldo berkurang + Revenue Sharing |
| F4 — Withdraw (interaksi saldo) | Permintaan withdraw | Dana di-hold → final/dilepas |
| F5 — Freeze / Unfreeze | Indikasi risiko | Mutasi dihentikan/dipulihkan |
| F6 — Reconciliation | Terjadwal (cron) | Cache saldo diselaraskan ke Ledger |

---

## 2. F1 — WALLET PROVISIONING

```mermaid
sequenceDiagram
    participant U as User
    participant IAM as Identity & Access
    participant W as Wallet Domain
    participant L as Ledger
    U->>IAM: Registrasi & verifikasi
    IAM-->>W: Event UserVerified
    W->>W: Buat Wallet (status Active)
    W->>L: Buka akun wallet (account)
    W-->>U: Wallet siap digunakan
```
**Aturan terkait:** BR-WALLET-001.
**Kegagalan:** bila pembuatan akun gagal, seluruh proses di-rollback; Wallet tidak setengah jadi.

---

## 3. F2 — TOP-UP

```mermaid
sequenceDiagram
    participant U as User
    participant PAY as Payment Domain
    participant GW as Payment Gateway
    participant W as Wallet Domain
    participant L as Ledger
    U->>PAY: Inisiasi top-up
    PAY->>GW: Buat order pembayaran
    GW-->>U: Halaman/instruksi pembayaran
    U->>GW: Melakukan pembayaran
    GW-->>PAY: Webhook: PAID (terverifikasi)
    PAY->>W: Top-up terkonfirmasi (idempotency_key)
    W->>L: Posting Transaction Topup (debit gateway_clearing, kredit wallet)
    W-->>U: Saldo bertambah
```
**Aturan terkait:** BR-WALLET-050, 051, 052, 090.
**Idempotency:** webhook ganda dengan key sama tidak menambah saldo dua kali.
**Kegagalan:** status `FAILED/EXPIRED` dari gateway → tidak ada mutasi nilai.

---

## 4. F3 — SPEND / PURCHASE (dengan Revenue Sharing)

```mermaid
sequenceDiagram
    participant A as Audience
    participant CNT as Content Domain
    participant W as Wallet Domain
    participant L as Ledger
    participant RS as Revenue Sharing
    A->>CNT: Beli konten/membership
    CNT->>W: Minta debit (idempotency_key)
    W->>W: Cek status wallet & saldo (BR-011/060)
    alt Saldo cukup & Active
        W->>L: Posting debit pembelian
        W->>RS: Picu pembagian nilai
        RS->>L: Posting alokasi (platform/creator/affiliate/mission)
        W-->>CNT: Sukses
        CNT-->>A: Akses konten diberikan
    else Ditolak
        W-->>CNT: Gagal (saldo/wallet)
        CNT-->>A: Pembelian gagal
    end
```
**Aturan terkait:** BR-WALLET-060, 061, 062, 031.
**Catatan:** debit pembelian & alokasi Revenue Sharing terjadi dalam satu transaksi logis yang seimbang (Σ debit = Σ kredit).

---

## 5. F4 — WITHDRAW (Interaksi Saldo)

```mermaid
stateDiagram-v2
    [*] --> Requested: user minta withdraw
    Requested --> Held: dana di-hold (BR-070)
    Held --> Approved: admin setuju
    Held --> Released: ditolak (BR-071)
    Approved --> Completed: dana cair (BR-072)
    Released --> [*]
    Completed --> [*]
```
**Aturan terkait:** BR-WALLET-070, 071, 072.
**Saldo tersedia** = saldo Ledger − dana yang sedang di-hold.
**Detail eksternal** (disbursement gateway) berada di Payment Domain.

---

## 6. F5 — FREEZE / UNFREEZE

```mermaid
sequenceDiagram
    participant AD as Admin
    participant W as Wallet Domain
    participant AU as Audit Log
    AD->>W: Freeze wallet (alasan)
    W->>W: Status → Frozen (BR-080/081)
    W->>AU: Catat aktor + alasan (BR-082)
    Note over W: Semua mutasi ditolak
    AD->>W: Unfreeze (alasan)
    W->>W: Status → Active
    W->>AU: Catat pencabutan
```
**Aturan terkait:** BR-WALLET-080, 081, 082.

---

## 7. F6 — RECONCILIATION (Terjadwal)

```mermaid
flowchart TD
    A[Cron terjadwal - cPanel] --> B[Hitung saldo dari Ledger]
    B --> C{Cocok dengan cache saldo?}
    C -- Ya --> D[Selesai - tidak ada aksi]
    C -- Tidak --> E[Koreksi cache mengikuti Ledger]
    E --> F[Catat selisih ke laporan audit]
    F --> G[Notifikasi Admin]
```
**Aturan terkait:** BR-WALLET-100, 101.
**Prinsip:** bila terjadi selisih, **Ledger selalu menang**.

---

## 8. PRINSIP PENANGANAN KEGAGALAN (Lintas Flow)

| Situasi | Penanganan |
|---|---|
| Request ganda (retry) | Idempotency key mencegah proses dobel (BR-090). |
| Kegagalan di tengah posting | Rollback penuh; tidak ada state setengah jadi. |
| Webhook gateway terlambat/ganda | Diproses idempotent; hanya satu yang berlaku. |
| Selisih saldo | Rekonsiliasi mengoreksi ke Ledger & melaporkan. |
| Pending menggantung | TTL menandai `Failed`; saldo tak terpengaruh (BR-034). |

---

## CHANGE LOG
| Versi | Tanggal | Perubahan |
|---|---|---|
| 1.0.0 | — | Penerbitan awal Wallet Flow (F1–F6) + penanganan kegagalan. |

**— Akhir Wallet Flow —**
