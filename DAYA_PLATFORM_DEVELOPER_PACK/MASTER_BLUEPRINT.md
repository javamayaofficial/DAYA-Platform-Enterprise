# DAYA PLATFORM — MASTER BLUEPRINT

> **Mission-Driven Creator Economy Platform**
> Dokumen ini adalah **peta induk (master index)** seluruh dokumentasi enterprise DAYA Platform.
> Berisi *struktur*, *tujuan*, *daftar dokumen*, dan *prioritas pengerjaan* — **belum berisi konten detail** tiap bagian.
> Setiap bagian dikerjakan satu per satu mengikuti **urutan Fase + Prioritas** pada dokumen ini.

---

## METADATA DOKUMEN

| Atribut | Nilai |
|---|---|
| Kode Dokumen | `DAYA-00-MASTER-BLUEPRINT` |
| Versi | `1.0` |
| Status | `🔒 FROZEN v1.0 — Acuan Resmi Proyek` |
| Tipe | Master Index / Work Breakdown |
| Pemilik (Owner) | Product & Architecture Team |
| Stack Target | PHP Native + MySQL + Bootstrap 5 |
| Constraint | cPanel / FastPanel · Shared Hosting · Tanpa Docker / NodeJS / SSH / Terminal |
| Bahasa Dokumen | Bahasa Indonesia (istilah teknis dalam Bahasa Inggris) |
| Diagram | ASCII + Mermaid (bila diperlukan) |
| Pengguna Acuan | Claude AI · ChatGPT · Nexla AI · TRAE AI · Developer |

---

## CHANGE LOG

| Versi | Tanggal | Perubahan |
|---|---|---|
| 0.1 | — | Draft struktur awal 25 bagian + 3 cross-cutting (usulan) |
| **1.0** | — | Resolusi keputusan D-01..D-04; cross-cutting (Glossary, Governance, ADR) disahkan; **+9 Foundational Sections** (26–34); urutan kerja ditetapkan **Fase + Prioritas**; ditambahkan **Foundational Governance Matrix**. Disiapkan untuk **freeze**. |

### Resolusi Keputusan (v1.0)

| # | Keputusan | Hasil |
|---|---|---|
| D-01 | Cross-Cutting docs (Glossary, Governance, ADR) | ✅ **Disahkan** |
| D-02 | Urutan pengerjaan | ✅ **Fase + Prioritas** |
| D-03 | Bahasa dokumen | ✅ **Bahasa Indonesia + istilah teknis Inggris** |
| D-04 | Format diagram | ✅ **ASCII + Mermaid bila diperlukan** |

---

## CARA MEMBACA BLUEPRINT INI

### Legenda Prioritas

| Kode | Arti | Penjelasan |
|---|---|---|
| **P0** | Critical / Fondasi | Wajib selesai lebih dulu. Menjadi referensi semua dokumen lain. |
| **P1** | High | Inti produk & sistem. Dikerjakan setelah P0. |
| **P2** | Medium | Penting, dikerjakan setelah inti sistem terdefinisi. |
| **P3** | Later / Living | Berkembang seiring waktu, sering diperbarui. |

### Legenda Fase Pengerjaan

| Fase | Nama | Fokus |
|---|---|---|
| **Fase 0** | Fondasi Strategis | Mengapa platform ini ada, ke mana arahnya, & cara kita berpikir |
| **Fase 1** | Definisi Produk & Aturan | Apa yang sistem lakukan, batas V1, & aturan mainnya |
| **Fase 2** | Desain Domain Bisnis | Mesin ekonomi: creator, mission, revenue, sharing, wallet, payment, content |
| **Fase 3** | Desain Teknis | Konfigurasi, data, arsitektur, API, keamanan |
| **Fase 4** | Pengalaman & Operasional | UI/UX, admin, analytics, notifikasi |
| **Fase 5** | Delivery & Evolusi | Deployment & roadmap jangka panjang |

### Konvensi Penamaan File

```
DAYA-[NN]-[KODE-SECTION]-[nama-dokumen].md

Contoh:
DAYA-26-PHILO-product-philosophy.md
DAYA-34-AUDIT-ledger-principles.md
DAYA-16-DB-entity-relationship-diagram.md
```

---

## MATRIKS PRIORITAS (RINGKASAN — URUTAN KERJA)

> Diurutkan berdasarkan **Fase → Prioritas → urutan logis**. Kolom `#` adalah ID katalog (stabil), bukan urutan kerja.

| Urut | # | Bagian | Kode | Prioritas | Fase |
|:---:|:---:|---|---|:---:|:---:|
| 1 | 2 | Vision, Mission & Core Values | `VMV` | P0 | 0 |
| 2 | 26 | Product Philosophy | `PHILO` | P0 | 0 |
| 3 | 27 | Product Principles | `PPRIN` | P0 | 0 |
| 4 | 28 | Design Principles | `DPRIN` | P0 | 0 |
| 5 | 3 | Business Model | `BMODEL` | P0 | 0 |
| 6 | 4 | Product Strategy | `PSTRAT` | P0 | 0 |
| 7 | 1 | Executive Summary | `EXEC` | P0 | 0 |
| 8 | 29 | MVP Scope | `MVP` | P0 | 1 |
| 9 | 30 | Non-Goals (V1) | `NONGOAL` | P0 | 1 |
| 10 | 5 | User Roles & Permissions | `RBAC` | P0 | 1 |
| 11 | 12 | Business Rules | `RULES` | P0 | 1 |
| 12 | 13 | Functional Requirements | `FR` | P1 | 1 |
| 13 | 14 | Non-Functional Requirements | `NFR` | P1 | 1 |
| 14 | 34 | Audit & Ledger Principles | `AUDIT` | P1 | 2 |
| 15 | 6 | Creator Economy | `CECON` | P1 | 2 |
| 16 | 32 | Mission & Foundation Model | `MISSION` | P1 | 2 |
| 17 | 7 | Revenue Model | `REV` | P1 | 2 |
| 18 | 31 | Revenue Sharing Engine | `REVSHARE` | P1 | 2 |
| 19 | 8 | Affiliate Engine | `AFFIL` | P1 | 2 |
| 20 | 9 | Wallet & Credit System | `WALLET` | P1 | 2 |
| 21 | 10 | Payment System | `PAY` | P1 | 2 |
| 22 | 11 | Content Engine | `CONTENT` | P1 | 2 |
| 23 | 15 | Information Architecture | `IA` | P1 | 3 |
| 24 | 33 | Configuration Engine | `CONFIG` | P1 | 3 |
| 25 | 16 | Database Blueprint | `DB` | P1 | 3 |
| 26 | 17 | System Architecture | `ARCH` | P1 | 3 |
| 27 | 20 | Security Architecture | `SEC` | P1 | 3 |
| 28 | 18 | API Blueprint | `API` | P2 | 3 |
| 29 | 19 | UI/UX Guidelines | `UIUX` | P2 | 4 |
| 30 | 21 | Admin Panel | `ADMIN` | P2 | 4 |
| 31 | 22 | Analytics | `ANALYTICS` | P2 | 4 |
| 32 | 23 | Notification System | `NOTIF` | P2 | 4 |
| 33 | 24 | Deployment Strategy | `DEPLOY` | P2 | 5 |
| 34 | 25 | Future Roadmap | `ROADMAP` | P3 | 5 |

---

## FOUNDATIONAL GOVERNANCE MATRIX

> 9 bagian fondasi (ditambahkan v1.0) beserta bagian hilir yang mereka ikat. Wajib dirujuk saat menyusun dokumen hilir.

| Foundational Section | Memengaruhi / Mengikat |
|---|---|
| 26. Product Philosophy | Seluruh keputusan → Principles, Strategy, Business Rules |
| 27. Product Principles | Product Strategy, MVP Scope, Business Rules, FR |
| 28. Design Principles | UI/UX Guidelines (#19), Admin Panel (#21) |
| 29. MVP Scope | Functional Requirements (#13), Database (#16), Roadmap (#25) |
| 30. Non-Goals (V1) | Functional Requirements (#13), Roadmap (#25) |
| 34. Audit & Ledger Principles | Wallet (#9), Payment (#10), Revenue Sharing (#31), Security (#20), Database (#16) |
| 32. Mission & Foundation Model | Business Rules (#12), Revenue Sharing (#31), Database (#16), Analytics (#22) |
| 31. Revenue Sharing Engine | Business Rules (#12), Wallet (#9), Database (#16) |
| 33. Configuration Engine | Database (#16), System Architecture (#17), Business Rules (#12) |

### Peta Ketergantungan (Dependency Map)

```
FASE 0 (Strategi & Cara Berpikir)
  VMV ─► PHILO ─► PPRIN ─► DPRIN ─► BMODEL ─► PSTRAT ─► EXEC
                                    │
                                    ▼
FASE 1 (Definisi & Batas)
  MVP ─ NONGOAL ─► RBAC ─► RULES ─► FR ─ NFR
                                    │
                                    ▼
FASE 2 (Domain Bisnis)
  AUDIT(prinsip) ─► CECON ─ MISSION ─► REV ─► REVSHARE ─► AFFIL ─► WALLET ─► PAY ─ CONTENT
                                    │
                                    ▼
FASE 3 (Teknis)
  IA ─► CONFIG ─► DB ─► ARCH ─► SEC ─► API
                                    │
                                    ▼
FASE 4 (Experience & Ops)
  UIUX ─ ADMIN ─ ANALYTICS ─ NOTIF
                                    │
                                    ▼
FASE 5 (Delivery)
  DEPLOY ─► ROADMAP

(Cross-Cutting / berlaku sepanjang proyek)
  GLOSSARY · GOVERNANCE · ADR · SEC · AUDIT · CONFIG
```

---

# LAPISAN CROSS-CUTTING (Bagian 0)

> ✅ Disahkan pada v1.0. Tiga dokumen ini menegakkan konsistensi & ketertelusuran lintas modul dan lintas AI.

### 0.1 Glossary / Ubiquitous Language
- **Tujuan:** Kamus istilah wajib agar semua AI tools & developer memakai terminologi seragam (mis. "Creator", "Mission Score", "Credit" vs "Wallet", "Foundation Fund").
- **Prioritas:** **P0 — Living Document** (mulai Fase 0).
- **Dokumen:** `[ ]` Glossary & Ubiquitous Language Dictionary

### 0.2 Document Governance & Naming Convention
- **Tujuan:** Mengatur penomoran, versioning, status, dan kepemilikan setiap dokumen.
- **Prioritas:** **P0 — Living Document.**
- **Dokumen:** `[ ]` Documentation Governance Guide

### 0.3 Architecture Decision Records (ADR)
- **Tujuan:** Mencatat keputusan teknis penting beserta alasannya (mis. "mengapa PHP Native, bukan framework").
- **Prioritas:** **P1 — Living Document.**
- **Dokumen:** `[ ]` ADR Log (ADR-001, ADR-002, ...)

---

# FASE 0 — FONDASI STRATEGIS

## (Urut 1) #2 — Vision, Mission & Core Values · `VMV`
- **Tujuan:** Menetapkan arah jangka panjang dan prinsip *mission-driven* yang menjadi kompas seluruh keputusan produk & teknis.
- **Deskripsi:** Mendefinisikan visi, misi, nilai inti, dan kerangka bagaimana "misi" diukur secara konkret.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Vision Statement
  - `[ ]` Mission Statement
  - `[ ]` Core Values & Guiding Principles
  - `[ ]` Mission-Driven Framework (definisi & metrik dampak)
- **Prioritas:** **P0** · Fase 0

## (Urut 2) #26 — Product Philosophy · `PHILO` 🆕
- **Tujuan:** Menetapkan cara berpikir fundamental yang melandasi setiap keputusan produk, agar "rasa" produk tetap konsisten meski dikerjakan banyak orang/AI.
- **Deskripsi:** Menjawab *mengapa kita membangun seperti ini* — keyakinan inti tentang kreator, misi, dan teknologi. Induk dari Product Principles & Business Rules.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Product Philosophy Statement
  - `[ ]` Core Beliefs & Assumptions
  - `[ ]` Trade-off Philosophy (apa yang dikorbankan demi apa)
- **Prioritas:** **P0** · Fase 0 · *Foundational*

## (Urut 3) #27 — Product Principles · `PPRIN` 🆕
- **Tujuan:** Mengubah filosofi menjadi prinsip operasional yang dapat diuji untuk memandu prioritisasi & keputusan fitur.
- **Deskripsi:** Daftar prinsip produk (mis. *Mission over vanity metrics*, *Creator-first*) dengan implikasi konkret dan heuristik keputusan.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Product Principles Catalog
  - `[ ]` Decision Heuristics (saat prinsip berbenturan)
- **Prioritas:** **P0** · Fase 0 · *Foundational*

## (Urut 4) #28 — Design Principles · `DPRIN` 🆕
- **Tujuan:** Menetapkan prinsip desain tingkat tinggi (mobile-first, clarity, trust) yang menjadi **induk** UI/UX Guidelines (#19).
- **Deskripsi:** Prinsip pengalaman & visual fondasional — berbeda dari UI/UX Guidelines yang lebih detail/komponen.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Design Principles Statement
  - `[ ]` Experience Pillars
  - `[ ]` Accessibility & Trust Principles
- **Prioritas:** **P0** · Fase 0 · *Foundational (induk #19)*

## (Urut 5) #3 — Business Model · `BMODEL`
- **Tujuan:** Mendefinisikan bagaimana DAYA menciptakan, memberikan, dan menangkap nilai secara berkelanjutan.
- **Deskripsi:** Model bisnis menyeluruh: segmen pelanggan, aliran nilai, struktur biaya, mitra kunci, dan posisi kompetitif.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Business Model Canvas
  - `[ ]` Customer Segments & Personas
  - `[ ]` Cost Structure & Unit Economics
  - `[ ]` Key Partnerships & Resources
  - `[ ]` Competitive Landscape Analysis
- **Prioritas:** **P0** · Fase 0

## (Urut 6) #4 — Product Strategy · `PSTRAT`
- **Tujuan:** Menetapkan strategi produk, positioning, diferensiasi, dan prinsip prioritisasi fitur.
- **Deskripsi:** North Star Metric, batasan ruang lingkup, dan strategi rilis.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Product Vision & Strategy
  - `[ ]` North Star Metric & KPI Tree
  - `[ ]` Feature Prioritization Framework (RICE / MoSCoW)
  - `[ ]` Go-to-Market Strategy
- **Prioritas:** **P0** · Fase 0

## (Urut 7) #1 — Executive Summary · `EXEC`
- **Tujuan:** Memberi gambaran tingkat eksekutif tentang DAYA — masalah, solusi, value proposition, target pasar, dampak misi — dipahami dalam <5 menit.
- **Deskripsi:** Dokumen pembuka yang merangkum keseluruhan blueprint. *Living document*, difinalisasi di akhir setelah bagian lain matang.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Executive Summary (1-page brief)
  - `[ ]` Problem Statement & Opportunity
  - `[ ]` Value Proposition Canvas
  - `[ ]` Stakeholder Map
- **Prioritas:** **P0** · Fase 0 *(draft awal → final di akhir)*

---

# FASE 1 — DEFINISI PRODUK & ATURAN

## (Urut 8) #29 — MVP Scope · `MVP` 🆕
- **Tujuan:** Mendefinisikan secara tegas fitur yang masuk Versi 1 agar tim & AI tidak over-build.
- **Deskripsi:** Daftar fitur in-scope V1, kriteria "selesai", dan urutan rilis MVP. Mengikat FR & Database.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` MVP Feature Scope
  - `[ ]` MVP Acceptance Criteria
  - `[ ]` MVP Release Sequence
- **Prioritas:** **P0** · Fase 1 · *Foundational*

## (Urut 9) #30 — Non-Goals (V1) · `NONGOAL` 🆕
- **Tujuan:** Menyatakan eksplisit apa yang **TIDAK** dibangun di V1 untuk mencegah scope creep.
- **Deskripsi:** Daftar fitur/skenario yang sengaja ditunda beserta alasannya; pasangan dari MVP Scope.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Non-Goals List (V1)
  - `[ ]` Deferred Features Rationale
- **Prioritas:** **P0** · Fase 1 · *Foundational*

## (Urut 10) #5 — User Roles & Permissions · `RBAC`
- **Tujuan:** Mendefinisikan seluruh aktor sistem dan model akses (RBAC) sebagai dasar keamanan, fitur, dan database.
- **Deskripsi:** Katalog peran (Guest, Member, Creator, Affiliate, Admin, Super Admin, dll), hierarki, dan matriks izin.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Actor Catalog
  - `[ ]` Role Hierarchy Diagram
  - `[ ]` RBAC Permission Matrix
  - `[ ]` Account Lifecycle (registrasi, verifikasi, suspensi, penghapusan)
- **Prioritas:** **P0** · Fase 1

## (Urut 11) #12 — Business Rules · `RULES`
- **Tujuan:** *Single source of truth* seluruh aturan bisnis lintas modul — referensi utama developer & AI.
- **Deskripsi:** Katalog aturan terstruktur dengan ID unik, kondisi, dan aksi. Dikerjakan paralel dengan domain bisnis & terus diperkaya.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Business Rules Catalog (terindeks per modul, mis. `BR-WALLET-001`)
  - `[ ]` Decision Tables
  - `[ ]` Validation & Constraint Rules
  - `[ ]` Edge Cases & Exception Handling
- **Prioritas:** **P0** · Fase 1 *(living, lintas fase)*

## (Urut 12) #13 — Functional Requirements · `FR`
- **Tujuan:** Mendefinisikan *apa* yang harus dilakukan sistem secara fungsional.
- **Deskripsi:** Hierarki Epic → Feature → User Story → Acceptance Criteria, lengkap dengan keterlacakan.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Feature Catalog / Module Map
  - `[ ]` User Stories & Acceptance Criteria
  - `[ ]` Use Case Specifications
  - `[ ]` Requirement Traceability Matrix
- **Prioritas:** **P1** · Fase 1

## (Urut 13) #14 — Non-Functional Requirements · `NFR`
- **Tujuan:** Mendefinisikan *kualitas* sistem (performa, skalabilitas, keandalan, keamanan) dalam batasan shared hosting.
- **Deskripsi:** Target metrik terukur (response time, uptime, concurrency) dan batasan teknis cPanel.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` NFR Specification (Performance, Scalability, Availability)
  - `[ ]` Shared Hosting Constraint Matrix
  - `[ ]` SLA / SLO Targets
  - `[ ]` Capacity Planning Assumptions
- **Prioritas:** **P1** · Fase 1

---

# FASE 2 — DESAIN DOMAIN BISNIS

## (Urut 14) #34 — Audit & Ledger Principles · `AUDIT` 🆕
- **Tujuan:** Menetapkan prinsip integritas finansial & ketertelusuran (immutability, double-entry, append-only) yang mengikat seluruh modul uang. **Mendahului** implementasi Wallet/Payment.
- **Deskripsi:** Prinsip dasar ledger & audit trail yang wajib dipatuhi Wallet (#9), Payment (#10), Revenue Sharing (#31).
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Ledger Principles (Double-Entry, Append-Only, Immutability)
  - `[ ]` Audit Trail Standards
  - `[ ]` Idempotency & Reconciliation Principles
  - `[ ]` Financial Data Integrity Rules
- **Prioritas:** **P1** · Fase 2 · *Foundational (cross-cutting financial)*

## (Urut 15) #6 — Creator Economy · `CECON`
- **Tujuan:** Mendefinisikan model ekonomi kreator — bagaimana creator membuat, mendistribusikan, dan memonetisasi nilai serta dampak misi.
- **Deskripsi:** Perjalanan kreator, sistem tier, mekanik monetisasi, dan model skor misi/dampak.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Creator Journey Map
  - `[ ]` Creator Tier & Leveling System
  - `[ ]` Monetization Mechanics
  - `[ ]` Mission / Impact Scoring Model
- **Prioritas:** **P1** · Fase 2

## (Urut 16) #32 — Mission & Foundation Model · `MISSION` 🆕
- **Tujuan:** Mendefinisikan inti *mission-driven* — bagaimana sebagian nilai mengalir ke misi/foundation dan bagaimana dampak diukur & dilaporkan.
- **Deskripsi:** Model alokasi misi (mis. % ke foundation/cause), tata kelola dana misi, dan pelaporan transparansi dampak.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Mission Allocation Model
  - `[ ]` Foundation Fund Governance
  - `[ ]` Impact Measurement & Reporting
  - `[ ]` Transparency & Disclosure Rules
- **Prioritas:** **P1** · Fase 2 · *Foundational*

## (Urut 17) #7 — Revenue Model · `REV`
- **Tujuan:** Mendefinisikan seluruh aliran pendapatan platform & kreator serta skema bagi hasil tingkat tinggi.
- **Deskripsi:** Katalog revenue stream (subscription, komisi, fee, credit), aturan revenue sharing, dan struktur biaya.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Revenue Streams Catalog
  - `[ ]` Revenue Sharing & Commission Rules (kebijakan tingkat tinggi)
  - `[ ]` Pricing & Fee Structure
  - `[ ]` Payout Policy
- **Prioritas:** **P1** · Fase 2

## (Urut 18) #31 — Revenue Sharing Engine · `REVSHARE` 🆕
- **Tujuan:** Mendefinisikan mesin pembagian pendapatan multi-pihak (platform, creator, foundation/mission, affiliate) secara akurat & dapat diaudit.
- **Deskripsi:** Aturan split, urutan pemotongan (waterfall), pembulatan, dan pencatatan ke ledger. Bergantung pada #7, #32, #8, #34.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Revenue Split Rules & Waterfall
  - `[ ]` Split Calculation & Rounding Policy
  - `[ ]` Multi-Party Distribution Flow (Mermaid)
  - `[ ]` Ledger Posting Specification
- **Prioritas:** **P1** · Fase 2 · *Foundational*

## (Urut 19) #8 — Affiliate Engine · `AFFIL`
- **Tujuan:** Mendefinisikan sistem afiliasi untuk pertumbuhan organik secara aman & adil.
- **Deskripsi:** Pelacakan referral, atribusi, tier komisi, dan pencegahan kecurangan.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Affiliate Program Rules
  - `[ ]` Referral & Attribution Model
  - `[ ]` Commission Calculation Logic
  - `[ ]` Anti-Fraud & Abuse Prevention
- **Prioritas:** **P1** · Fase 2

## (Urut 20) #9 — Wallet & Credit System · `WALLET`
- **Tujuan:** Mendefinisikan dompet internal, kredit, dan buku besar transaksi yang dapat diaudit.
- **Deskripsi:** Saldo wallet, kredit (top-up/spend), *double-entry ledger* (patuh #34), dan rekonsiliasi. **Modul berisiko finansial.**
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Wallet & Credit Concept
  - `[ ]` Ledger & Transaction Model (double-entry)
  - `[ ]` Top-up / Spend / Withdraw Flow
  - `[ ]` Reconciliation & Audit Rules
- **Prioritas:** **P1** · Fase 2

## (Urut 21) #10 — Payment System · `PAY`
- **Tujuan:** Mendefinisikan integrasi pembayaran, settlement, dan pencairan.
- **Deskripsi:** Integrasi payment gateway (mis. Midtrans/Xendit/Duitku), alur checkout, penanganan webhook, dan disbursement.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Payment Gateway Integration Spec
  - `[ ]` Payment & Checkout Flow
  - `[ ]` Webhook & Settlement Handling
  - `[ ]` Withdrawal / Disbursement Flow
  - `[ ]` Compliance & PCI Considerations
- **Prioritas:** **P1** · Fase 2

## (Urut 22) #11 — Content Engine · `CONTENT`
- **Tujuan:** Mendefinisikan sistem pengelolaan konten (produk digital, kelas, artikel, media).
- **Deskripsi:** Tipe konten, siklus hidup, moderasi, strategi penyimpanan media di shared hosting, dan SEO.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Content Type Taxonomy
  - `[ ]` Content Lifecycle & Workflow
  - `[ ]` Moderation & Approval Rules
  - `[ ]` Media Storage & Delivery Strategy (shared hosting)
  - `[ ]` SEO Content Model
- **Prioritas:** **P1** · Fase 2

---

# FASE 3 — DESAIN TEKNIS

## (Urut 23) #15 — Information Architecture · `IA`
- **Tujuan:** Mendefinisikan struktur informasi, navigasi, dan taksonomi platform.
- **Deskripsi:** Sitemap, model navigasi, struktur URL (SEO-friendly), dan hierarki konten.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Sitemap (Public + Dashboard)
  - `[ ]` Navigation & Menu Structure
  - `[ ]` URL & Routing Convention (SEO-friendly)
  - `[ ]` Taxonomy & Tagging Model
- **Prioritas:** **P1** · Fase 3

## (Urut 24) #33 — Configuration Engine · `CONFIG` 🆕
- **Tujuan:** Mendefinisikan apa yang dapat dikonfigurasi (fee, komisi, % misi, fitur) tanpa mengubah kode — fondasi fleksibilitas SaaS. **Mendahului** Database Blueprint.
- **Deskripsi:** Parameter konfigurasi terpusat, hierarki override (global → tenant → user), dan dampaknya pada skema DB & arsitektur. Menentukan tabel konfigurasi vs nilai hardcoded.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Configuration Catalog (parameter & default)
  - `[ ]` Config Hierarchy & Override Rules
  - `[ ]` Feature Flags Strategy
  - `[ ]` Config Storage & Caching Design
- **Prioritas:** **P1** · Fase 3 · *Foundational*

## (Urut 25) #16 — Database Blueprint · `DB`
- **Tujuan:** Mendefinisikan skema data lengkap, relasi, indexing, dan strategi integritas.
- **Deskripsi:** ERD, kamus data, normalisasi, strategi indexing, dan migrasi ramah cPanel (tanpa CLI). **Bergantung pada #5, #12, #29, #31–34, #6–11.**
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Entity Relationship Diagram (ERD — Mermaid)
  - `[ ]` Data Dictionary / Table Specifications
  - `[ ]` Indexing & Performance Strategy
  - `[ ]` Migration & Seeding Strategy (cPanel-friendly)
  - `[ ]` Data Retention & Archiving Policy
- **Prioritas:** **P1** · Fase 3

## (Urut 26) #17 — System Architecture · `ARCH`
- **Tujuan:** Mendefinisikan arsitektur teknis menyeluruh dalam batasan PHP Native + shared hosting.
- **Deskripsi:** Arsitektur modular berlapis, struktur folder, alur request/routing, strategi caching — tanpa Docker/Node/SSH.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` High-Level Architecture Diagram (Mermaid)
  - `[ ]` Modular Folder Structure & Conventions
  - `[ ]` Request Lifecycle / Routing Design
  - `[ ]` Caching & Performance Strategy
  - `[ ]` Third-Party Integration Map
  - `[ ]` Architecture Decision Records (referensi ADR Log)
- **Prioritas:** **P1** · Fase 3

## (Urut 27) #20 — Security Architecture · `SEC`
- **Tujuan:** Mendefinisikan postur keamanan menyeluruh (authn, authz, proteksi data, OWASP).
- **Deskripsi:** Threat model, mitigasi OWASP Top 10, enkripsi, manajemen sesi, dan audit. **Lintas fase — mulai dipikirkan sejak awal.**
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Security Policy & Threat Model
  - `[ ]` Authentication & Session Management Spec
  - `[ ]` OWASP Top 10 Mitigation Checklist
  - `[ ]` Data Protection & Encryption Standards
  - `[ ]` Audit Logging & Incident Response
- **Prioritas:** **P1** · Fase 3 *(cross-cutting)*

## (Urut 28) #18 — API Blueprint · `API`
- **Tujuan:** Mendefinisikan kontrak REST API untuk mobile-first & integrasi eksternal.
- **Deskripsi:** Katalog endpoint, spesifikasi request/response, autentikasi, versioning, dan format error standar.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` API Design Standards & Conventions
  - `[ ]` Endpoint Catalog (OpenAPI-style)
  - `[ ]` Authentication & Authorization (Token / JWT)
  - `[ ]` Error Handling & Response Format
  - `[ ]` Rate Limiting & Versioning Policy
- **Prioritas:** **P2** · Fase 3

---

# FASE 4 — PENGALAMAN & OPERASIONAL

## (Urut 29) #19 — UI/UX Guidelines · `UIUX`
- **Tujuan:** Menetapkan standar desain mobile-first & design system berbasis Bootstrap 5. *(Turunan dari Design Principles #28.)*
- **Deskripsi:** Design tokens, komponen, pola interaksi, aksesibilitas, dan aturan responsif.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Design System / Component Library (Bootstrap 5)
  - `[ ]` Responsive & Mobile-First Patterns
  - `[ ]` Accessibility (a11y) Standards
  - `[ ]` Key Screen Wireframes / User Flows
- **Prioritas:** **P2** · Fase 4

## (Urut 30) #21 — Admin Panel · `ADMIN`
- **Tujuan:** Mendefinisikan kebutuhan back-office untuk operasional, moderasi, dan kontrol finansial.
- **Deskripsi:** Modul admin, dashboard, manajemen user/konten, dan kontrol payout.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Admin Module Map
  - `[ ]` Admin Feature Specifications
  - `[ ]` Moderation & Approval Workflows
  - `[ ]` Financial & Payout Controls
- **Prioritas:** **P2** · Fase 4

## (Urut 31) #22 — Analytics · `ANALYTICS`
- **Tujuan:** Mendefinisikan kebutuhan data & metrik untuk pengambilan keputusan.
- **Deskripsi:** Taksonomi event, dashboard (creator & admin), dan pelacakan KPI.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Metrics & KPI Catalog
  - `[ ]` Event Tracking Taxonomy
  - `[ ]` Dashboard Specifications (Creator & Admin)
  - `[ ]` Reporting & Export Requirements
- **Prioritas:** **P2** · Fase 4

## (Urut 32) #23 — Notification System · `NOTIF`
- **Tujuan:** Mendefinisikan sistem notifikasi multi-channel (email, in-app, push/WA).
- **Deskripsi:** Tipe notifikasi, pemicu, template, dan mekanisme pengiriman berbasis cron cPanel.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Notification Catalog & Triggers
  - `[ ]` Channel Strategy (Email, In-App, Push/WA)
  - `[ ]` Template & Localization Standards
  - `[ ]` Queue & Delivery Mechanism (cron-based)
- **Prioritas:** **P2** · Fase 4

---

# FASE 5 — DELIVERY & EVOLUSI

## (Urut 33) #24 — Deployment Strategy · `DEPLOY`
- **Tujuan:** Mendefinisikan strategi rilis & operasional di cPanel/FastPanel tanpa CI/CD modern.
- **Deskripsi:** Alur deployment (File Manager/Git), manajemen environment, backup, cron, dan monitoring.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Deployment Workflow (cPanel / FastPanel)
  - `[ ]` Environment & Configuration Management
  - `[ ]` Backup & Disaster Recovery Plan
  - `[ ]` Cron Jobs & Scheduled Tasks
  - `[ ]` Monitoring & Maintenance Plan
- **Prioritas:** **P2** · Fase 5

## (Urut 34) #25 — Future Roadmap · `ROADMAP`
- **Tujuan:** Menetapkan visi pengembangan jangka panjang & jalur evolusi platform.
- **Deskripsi:** Roadmap bertahap, jalur scaling (migrasi ke VPS/Cloud), dan evolusi menuju SaaS multi-tenant.
- **Dokumen yang Harus Dibuat:**
  - `[ ]` Product Roadmap (Now / Next / Later)
  - `[ ]` Technical Scaling Roadmap (VPS / Cloud Migration Path)
  - `[ ]` SaaS Multi-Tenancy Evolution Plan
  - `[ ]` Feature Backlog
- **Prioritas:** **P3** · Fase 5 *(living document)*

---

## CHECKLIST PROGRES MASTER (Status Pengerjaan)

> Centang saat seluruh dokumen sebuah bagian selesai disusun. (🆕 = ditambahkan v1.0)

**Cross-Cutting (Living)**
- `[ ]` 0.1 Glossary / Ubiquitous Language
- `[ ]` 0.2 Document Governance & Naming Convention
- `[ ]` 0.3 Architecture Decision Records

**Fase 0 — Fondasi Strategis**
- `[ ]` #2 Vision, Mission & Core Values
- `[ ]` #26 Product Philosophy 🆕
- `[ ]` #27 Product Principles 🆕
- `[ ]` #28 Design Principles 🆕
- `[ ]` #3 Business Model
- `[ ]` #4 Product Strategy
- `[ ]` #1 Executive Summary

**Fase 1 — Definisi Produk & Aturan**
- `[ ]` #29 MVP Scope 🆕
- `[ ]` #30 Non-Goals (V1) 🆕
- `[ ]` #5 User Roles & Permissions
- `[ ]` #12 Business Rules
- `[ ]` #13 Functional Requirements
- `[ ]` #14 Non-Functional Requirements

**Fase 2 — Desain Domain Bisnis**
- `[ ]` #34 Audit & Ledger Principles 🆕
- `[ ]` #6 Creator Economy
- `[ ]` #32 Mission & Foundation Model 🆕
- `[ ]` #7 Revenue Model
- `[ ]` #31 Revenue Sharing Engine 🆕
- `[ ]` #8 Affiliate Engine
- `[ ]` #9 Wallet & Credit System
- `[ ]` #10 Payment System
- `[ ]` #11 Content Engine

**Fase 3 — Desain Teknis**
- `[ ]` #15 Information Architecture
- `[ ]` #33 Configuration Engine 🆕
- `[ ]` #16 Database Blueprint
- `[ ]` #17 System Architecture
- `[ ]` #20 Security Architecture
- `[ ]` #18 API Blueprint

**Fase 4 — Pengalaman & Operasional**
- `[ ]` #19 UI/UX Guidelines
- `[ ]` #21 Admin Panel
- `[ ]` #22 Analytics
- `[ ]` #23 Notification System

**Fase 5 — Delivery & Evolusi**
- `[ ]` #24 Deployment Strategy
- `[ ]` #25 Future Roadmap

---

## STATUS FREEZE

| Item | Status |
|---|---|
| Struktur 25 bagian awal | ✅ Disetujui |
| Keputusan D-01..D-04 | ✅ Diresolusi |
| 3 Cross-Cutting docs | ✅ Disahkan |
| 9 Foundational Sections (26–34) | ✅ Ditambahkan |
| Foundational Governance Matrix | ✅ Ditambahkan |
| Urutan kerja (Fase + Prioritas) | ✅ Ditetapkan |
| **Freeze v1.0** | 🔒 **DISAHKAN — Frozen (Acuan Resmi Proyek)** |

> Setelah sign-off, status berubah menjadi `🔒 FROZEN v1.0 — Acuan Resmi Proyek` dan pengerjaan dimulai dari **Urut 1 (#2 — Vision, Mission & Core Values)**.

---

*Akhir Master Blueprint v1.0. Konten tiap bagian disusun satu per satu setelah freeze disetujui, mengikuti kolom "Urut" pada Matriks Prioritas.*
