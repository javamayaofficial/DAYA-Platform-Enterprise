<!--
  File ini ditujukan untuk lokasi: docs/PROJECT_BIBLE.md
  Source of Truth: Repository GitHub (kode > docs/ > ai/ > DAYA_PLATFORM_DEVELOPER_PACK/ > README.md)
-->

# DAYA PLATFORM ENTERPRISE — PROJECT BIBLE

> **Dokumen paling penting di proyek DAYA Platform Enterprise.**
> Setiap kontributor — manusia maupun AI — **wajib membaca dokumen ini sepenuhnya sebelum mulai bekerja.**
> Dokumen ini berfungsi sekaligus sebagai *Enterprise Engineering Handbook*, *Software Architecture Handbook*, *AI Constitution*, *Developer Handbook*, *Technical Bible*, *Business Architecture*, dan *Governance Document*.

---

## METADATA DOKUMEN

| Atribut | Nilai |
|---|---|
| **Document Name** | `PROJECT_BIBLE.md` |
| **Version** | `1.0.0` |
| **Status** | `ACTIVE` |
| **Document Type** | Enterprise Engineering Handbook |
| **Repository** | DAYA Platform Enterprise |
| **Source of Truth** | GitHub Repository (Branch `main`) |
| Kode Dokumen | `DAYA-00-PROJECT-BIBLE` |
| Lokasi Kanonik | `docs/PROJECT_BIBLE.md` |
| Induk Konseptual | `DAYA-00-PROJECT-CONSTITUTION` · `DAYA-00-MASTER-BLUEPRINT` (Frozen v1.0) |
| Stack Target | PHP Native 8.x + MySQL (InnoDB) + Bootstrap 5 + Vanilla JS |
| Constraint Operasional | cPanel / FastPanel · Shared Hosting · Tanpa Docker / NodeJS / SSH / Terminal |
| Bahasa | Bahasa Indonesia profesional (istilah teknis tetap dalam Bahasa Inggris) |

### Informasi Tata Kelola

| Atribut | Nilai |
|---|---|
| **Created By** | Chief Software Architect — Java Maya Studio |
| **Maintained By** | Product & Architecture Team (DAYA Platform Enterprise) |
| **Last Updated** | 2026-06-29 |
| **Next Review** | 2026-09-29 (tinjauan kuartalan, atau lebih awal bila ada perubahan arsitektur/finansial) |
| **Repository URL** | `<TBD — isi URL repository GitHub resmi>` |
| **License** | Proprietary — © Java Maya Official *(TBD — konfirmasi lisensi resmi)* |
| **Document Priority** | **CRITICAL** — wajib dibaca sebelum berkontribusi |
| **Audience** | Seluruh Developer · AI Contributor (ChatGPT, Claude, Nexla, TRAE) · Reviewer · Architect · Product/Stakeholder |
| **Related Documents** | `PROJECT_CONSTITUTION.md` · `MASTER_BLUEPRINT.md` · `DOMAIN_MODEL.md` · `CODING_STANDARD.md` · `MODULE_TEMPLATE.md` · `ARCHITECTURE_DECISION.md` · `CONTENT_ARCHITECTURE_DECISION.md` · `IMPLEMENTATION_SEQUENCE.md` |

> **Catatan field bertanda `TBD`.** Sesuai prinsip "tanpa asumsi", *Repository URL* dan *License* dibiarkan sebagai placeholder eksplisit hingga Owner mengonfirmasi nilainya. Jangan mengisi dengan tebakan.

---

## DOCUMENT GOVERNANCE

> Bab ini mengatur status hukum dokumen: tujuannya, siapa yang berwenang mengubahnya, dan bagaimana perubahan disahkan. Mengikuti semangat *Microsoft Engineering Handbook* dan *Google Engineering Practices* tentang dokumen sebagai artefak yang diatur (*governed artifact*), bukan catatan bebas.

### G.1 Tujuan Dokumen

PROJECT_BIBLE berfungsi sebagai **sumber tunggal pedoman proyek** yang menyatukan strategi bisnis, standar rekayasa, alur kerja, tata kelola AI, dan keputusan arsitektur. Tujuannya:

- Memberi **konteks dan aturan yang identik** bagi seluruh kontributor manusia dan AI.
- Mencegah **drift** — perbedaan pemahaman atau penyimpangan dari arsitektur yang disepakati.
- Menjadi **titik rujuk resmi** ketika terjadi perselisihan interpretasi (sesudah kode sebagai Source of Truth).

### G.2 Siapa yang Boleh Mengubah

| Peran | Hak atas Dokumen |
|---|---|
| **Chief Software Architect / Owner** | Berwenang menyetujui & menggabungkan (*merge*) perubahan; satu-satunya yang dapat menaikkan MAJOR version. |
| **Product & Architecture Team** | Mengusulkan & menyusun perubahan; melakukan review teknis. |
| **Reviewer (manusia/AI)** | Menelaah konsistensi & dampak; memberi rekomendasi, bukan persetujuan final. |
| **Developer / AI Contributor** | Mengusulkan perubahan via Change Request; **tidak** boleh mengubah dokumen secara sepihak. |

> **Aturan mutlak untuk AI.** Tidak ada AI yang boleh mengubah arsitektur, standar, atau dokumen ini tanpa Change Request yang disetujui. Detail kewajiban AI ada di **Bagian IV — AI Constitution**.

### G.3 Cara Melakukan Perubahan

```
1. Buka Change Request (CR)         → jelaskan apa & mengapa
2. Impact Analysis                  → modul/dokumen terdampak
3. Susun perubahan di branch        → feature/bible-<ringkas>
4. Review                           → teknis + (bila finansial/keamanan) review ketat
5. Approval Owner                   → wajib
6. Update Version + Change Log      → §40
7. Merge ke main                    → main = Source of Truth
```

### G.4 Approval Workflow

```
        ┌──────────────┐
        │ Change Request│
        └──────┬───────┘
               ▼
        ┌──────────────┐      Menyentuh modul
        │Impact Analysis│──── finansial / keamanan? ──┐
        └──────┬───────┘                              │
               │ Tidak                            Ya  ▼
               ▼                          ┌──────────────────────┐
        ┌──────────────┐                  │ Review Ketat:        │
        │Review Standar │                  │ Architect + Security │
        └──────┬───────┘                  └──────────┬───────────┘
               └───────────────┬──────────────────────┘
                               ▼
                       ┌───────────────┐
                       │ Approval Owner │
                       └───────┬───────┘
                               ▼
              ┌────────────────────────────────┐
              │ Update Version + Change Log     │
              │ Merge ke branch main (SoT)      │
              └────────────────────────────────┘
```

### G.5 Versioning Rules

Mengikuti **Semantic Versioning** `MAJOR.MINOR.PATCH` selaras Project Constitution §12:

| Segmen | Dinaikkan saat | Contoh |
|---|---|---|
| **MAJOR** | Perubahan mengikat & tidak kompatibel mundur (mis. mengubah prinsip arsitektur/ledger, mencabut standar). | `1.0.0` → `2.0.0` |
| **MINOR** | Penambahan bab/standar baru yang kompatibel. | `1.0.0` → `1.1.0` |
| **PATCH** | Koreksi redaksional, klarifikasi, perbaikan tautan. | `1.0.0` → `1.0.1` |

Aturan tambahan: setiap rilis dokumen wajib memiliki entri **Change Log (§40)**; perubahan yang menyentuh standar finansial/keamanan dihitung **minimal MINOR**.

### G.6 Change Management

- Dokumen ber-status `ACTIVE` hanya diubah melalui mekanisme di §G.3–§G.4 — tidak ada perubahan langsung tanpa Change Request tercatat.
- Setiap perubahan **wajib** tercatat di GitHub (commit + PR + Change Log).
- Bila perubahan dokumen ini berdampak pada standar implementasi, dokumen turunan terkait (`CODING_STANDARD.md`, `MODULE_TEMPLATE.md`, dll.) **wajib** ikut diperbarui dalam PR yang sama atau PR tertaut.
- **Dokumentasi yang usang dianggap cacat (*defect*).** Memperbarui dokumen adalah bagian dari *Definition of Done*.

### G.7 Posisi PROJECT_BIBLE dalam Rantai Proyek

Dokumen ini berdiri di puncak rantai artefak: keputusan mengalir ke bawah dari Bible menuju produksi, dan umpan balik mengalir kembali ke atas.

```
        ┌──────────────────────────┐
        │      PROJECT_BIBLE       │  ← pedoman & governance tertinggi (dokumen)
        └────────────┬─────────────┘
                     ▼
        ┌──────────────────────────┐
        │      ARCHITECTURE        │  ← prinsip & keputusan arsitektur (§10, §34, §35)
        └────────────┬─────────────┘
                     ▼
        ┌──────────────────────────┐
        │     DOCUMENTATION        │  ← standar & dokumen modul (docs/, modul *.md)
        └────────────┬─────────────┘
                     ▼
        ┌──────────────────────────┐
        │      SOURCE CODE         │  ← implementasi (SOURCE OF TRUTH)
        └────────────┬─────────────┘
                     ▼
        ┌──────────────────────────┐
        │        TESTING           │  ← QA + security + Definition of Done
        └────────────┬─────────────┘
                     ▼
        ┌──────────────────────────┐
        │       DEPLOYMENT         │  ← cPanel / FastPanel · migrasi SQL manual
        └────────────┬─────────────┘
                     ▼
        ┌──────────────────────────┐
        │       PRODUCTION         │  ← shared hosting · monitoring · backup
        └────────────┬─────────────┘
                     │
                     └──────────► umpan balik kembali ke PROJECT_BIBLE
```

> **Prinsip rantai:** keputusan tidak boleh "melompati" lapisan. Kode tidak menyimpang dari Architecture; Architecture tidak menyimpang dari Bible. Bila kenyataan di Source Code menuntut perubahan prinsip, jalurnya adalah Change Request yang memperbarui Bible — bukan diam-diam menyimpang.

---

## SOURCE OF TRUTH & HIRARKI ACUAN

Bila terjadi perbedaan antara dokumen dan kode, **implementasi pada repository GitHub adalah acuan utama.** Urutan prioritas referensi:

| Prioritas | Sumber | Peran |
|:---:|---|---|
| 1 | **Source Code** (Repository GitHub) | Kebenaran final atas perilaku & status sistem |
| 2 | `docs/` | Standar baseline framework yang berlaku untuk kode |
| 3 | `ai/` | Tata kelola kolaborasi AI |
| 4 | `DAYA_PLATFORM_DEVELOPER_PACK/` | Spesifikasi target & blueprint (sebagian masih rencana) |
| 5 | `README.md` | Ringkasan status & orientasi awal |

> **Catatan Rekonsiliasi Penting.** `DAYA_PLATFORM_DEVELOPER_PACK/` adalah korpus *perencanaan* yang menargetkan modul finansial (Wallet sebagai pilot). Sebaliknya, *kode nyata* di repository telah mengimplementasikan modul **Authentication, Creator, Content, dan Collection**, sementara **Wallet belum berkode**. Bila terdapat konflik status, **kode-lah yang berlaku**. Pemetaan rinci kode vs dokumen disajikan pada **Lampiran B — Peta Modul & Status Implementasi**.

---

## LEGENDA STATUS

| Simbol | Arti |
|:---:|---|
| ✅ | Selesai / terimplementasi & terverifikasi di kode |
| 🟡 | Sebagian / domain siap, implementasi belum lengkap |
| 🔴 | Belum dikerjakan / draft |
| 🔒 | Frozen — hanya diubah lewat Change Management |
| 🆕 | Ditambahkan pada versi terbaru |

---

## MASTER TABLE OF CONTENTS

> Dokumen disusun bertahap. Tanda status menunjukkan progres penulisan **bab di dalam PROJECT_BIBLE ini** (bukan status implementasi modul).

### BAGIAN I — FOUNDATION  ✅
1. [Executive Summary](#1-executive-summary)
2. [Vision](#2-vision)
3. [Mission](#3-mission)
4. [Core Values](#4-core-values)
5. [Business Philosophy](#5-business-philosophy)
6. [Creator Economy Philosophy](#6-creator-economy-philosophy)
7. [Business Model](#7-business-model)
8. [Platform Scope](#8-platform-scope)

### BAGIAN II — ENGINEERING  ✅
9. [Technology Stack](#9-technology-stack)
10. [Architecture Philosophy](#10-architecture-philosophy)
11. [Folder Structure](#11-folder-structure)
12. [Database Philosophy](#12-database-philosophy)
13. [Coding Standard](#13-coding-standard)
14. [Module Standard](#14-module-standard)
15. [Security Standard](#15-security-standard)
16. [Performance Standard](#16-performance-standard)
17. [UI / UX Standard](#17-ui--ux-standard)

### BAGIAN III — DEVELOPMENT  ✅
18. [Git Workflow](#18-git-workflow)
19. [Development Workflow](#19-development-workflow)
20. [Documentation Workflow](#20-documentation-workflow)
21. [Testing Workflow](#21-testing-workflow)
22. [Deployment Workflow](#22-deployment-workflow)
23. [Release Management](#23-release-management)

### BAGIAN IV — AI  ✅
24. [AI Constitution](#24-ai-constitution)
25. [AI Workflow](#25-ai-workflow)
26. [AI Prompt Standard](#26-ai-prompt-standard)
27. [AI Handover Standard](#27-ai-handover-standard)
28. [AI Memory Standard](#28-ai-memory-standard)

### BAGIAN V — ENTERPRISE GOVERNANCE  ✅
29. [Business Rules](#29-business-rules)
30. [Revenue Sharing Philosophy](#30-revenue-sharing-philosophy)
31. [Governance Rules](#31-governance-rules)
32. [Risk Management](#32-risk-management)
33. [Future Vision](#33-future-vision)

### PENUTUP & LAMPIRAN  ✅
- [Closing — Document Status & Approval](#closing--document-status--approval)
- [Appendix A — Glossary](#appendix-a--glossary)
- [Appendix B — Master Document Matrix](#appendix-b--master-document-matrix)
- [Appendix C — Repository Structure](#appendix-c--repository-structure)
- [Appendix D — Module Status Matrix](#appendix-d--module-status-matrix)
- [Appendix E — Cross Reference Matrix](#appendix-e--cross-reference-matrix)
- [Appendix F — Documentation Priority](#appendix-f--documentation-priority)
- [PROJECT BIBLE — Completion Report](#project-bible--completion-report)

> **Catatan penyelarasan struktur (v1.0).** Dokumen difinalisasi dengan **Future Vision sebagai Bab 33**. Materi yang sempat dicadangkan pada rencana awal dipetakan ulang: **Roadmap & Future Vision → §33**; **Change Log → Closing**; **Design Principles, ADR, Non-Functional Requirements → ditangguhkan ke v1.1** (lihat catatan di Closing). Seluruh cross-reference lama ke §34–§40 mengikuti pemetaan ini.

---

## CARA MEMBACA DOKUMEN INI

- **Developer baru** → baca Bagian I (konteks), lalu Bagian II–III (cara bekerja), lalu modul yang ditugaskan.
- **AI contributor** → baca **seluruh** dokumen, terutama **Bagian IV — AI Constitution**, sebelum menghasilkan apa pun.
- **Reviewer / Architect** → fokus Bagian V untuk governance, risiko, dan keputusan tata kelola.
- **Product / Stakeholder** → fokus Bagian I dan Bab 33 (Future Vision).

Konvensi: setiap klaim teknis bertumpu pada repository. Hal yang **belum** final ditandai dengan rujukan ke dokumen sumbernya dan label statusnya, bukan diasumsikan.

---
---

# BAGIAN I — FOUNDATION

> Bagian ini menetapkan *mengapa* DAYA Platform ada dan *nilai* apa yang dijaga. Seluruh keputusan teknis pada bagian berikutnya harus dapat ditelusuri kembali ke fondasi ini.

---

## 1. EXECUTIVE SUMMARY

**DAYA Platform Enterprise** adalah sebuah *Mission-Driven Creator Economy Platform* — platform ekonomi kreator berbasis misi yang menghubungkan **creator**, **audiens**, dan **affiliate** dalam satu ekosistem. Karakter pembeda utamanya: setiap transaksi tidak hanya menghasilkan nilai ekonomi bagi creator, tetapi juga mengalirkan sebagian nilai ke **misi sosial** melalui *Mission & Foundation Model* yang transparan dan dapat diaudit.

Platform dibangun di atas **PHP Native + MySQL + Bootstrap 5 + Vanilla JS**, dirancang untuk berjalan penuh di **shared hosting** (cPanel / FastPanel) tanpa Docker, NodeJS, SSH, atau Terminal sebagai prasyarat. Pilihan ini bukan keterbatasan sementara, melainkan keputusan arsitektur sadar: *constraint sebagai fitur* yang memaksa kesederhanaan dan menjaga inklusivitas biaya.

### 1.1 Posisi Saat Ini (Ringkas)

| Aspek | Kondisi |
|---|---|
| Pola arsitektur | Modular Monolith + Front Controller + Thin Controller / Rich Service + Repository Pattern |
| Fondasi framework | ✅ Selesai — `Application`, `Router`, `ModuleManager`, `Autoloader`, `Config`, `Env`, `ErrorHandler`, `Http\*`, `Logger`, base layer `Modular/` |
| Modul terimplementasi | ✅ Authentication & RBAC · ✅ Creator · ✅ Content · ✅ Collection |
| Modul terencana (belum berkode) | 🔴 Wallet · Payment (Duitku) · Affiliate · Notification · Analytics · Sponsor · Foundation |
| Korpus dokumentasi | Master Blueprint v1.0 (🔒 Frozen), Project Constitution v1.0.0, Domain Model (21 entity / 7 bounded context), Developer Pack, TRAE Project Kit |
| Lingkungan deployment | Shared hosting via cPanel/FastPanel; migrasi SQL manual via phpMyAdmin |

### 1.2 Apa yang Dijaga di Atas Segalanya

Urutan prioritas trade-off resmi proyek (dari yang paling tidak boleh dikompromikan):

```
Integritas Finansial → Keamanan → Kejelasan/Maintainability → Performa → Kecepatan Pengiriman Fitur
```

Konsekuensi praktisnya: modul finansial diperlakukan dengan kehati-hatian tertinggi, kebenaran finansial **selalu** berada di **Ledger** (double-entry, append-only, immutable), dan tidak ada satu pun pergerakan dana yang boleh tanpa jejak audit.

### 1.3 Untuk Siapa Dokumen Ini

Dokumen ini adalah pedoman resmi seluruh **developer, AI, reviewer, dan kontributor** sepanjang siklus hidup proyek. Membaca PROJECT_BIBLE adalah syarat masuk (*entry requirement*) sebelum berkontribusi.

> **Cross-reference:** Konteks strategis lengkap → §2–§8. Cara membangun → Bagian II–III. Aturan AI → Bagian IV. Governance → Bagian V.

---

## 2. VISION

> **Visi:** Menjadi platform ekonomi kreator berbasis misi yang **paling tepercaya di kawasan**, tempat setiap karya kreator menciptakan nilai ekonomi sekaligus dampak sosial yang **nyata dan dapat diaudit**.

Visi ini diterjemahkan menjadi empat pilar jangka panjang yang menjadi kompas setiap keputusan:

| # | Pilar Visi | Makna |
|:---:|---|---|
| 1 | **Ekonomi yang Adil** | Creator memperoleh bagian nilai yang adil dan transparan dari setiap karya mereka. |
| 2 | **Misi yang Terukur** | Setiap rupiah yang mengalir ke misi sosial dapat dilacak, diaudit, dan dilaporkan secara publik. |
| 3 | **Skala Enterprise** | Berevolusi dari satu instans menjadi platform SaaS multi-tenant tanpa kehilangan integritas data finansial. |
| 4 | **Aksesibilitas** | Dapat dijalankan di infrastruktur terjangkau (shared hosting), inklusif bagi creator di berbagai tingkat ekonomi. |

**Uji keselarasan visi (litmus test).** Sebuah inisiatif selaras dengan visi bila menjawab "ya" pada pertanyaan berikut:

- [ ] Apakah ini menambah keadilan atau transparansi nilai bagi creator?
- [ ] Apakah dampak misinya dapat diukur dan diaudit?
- [ ] Apakah ini tetap kompatibel dengan jalur evolusi menuju SaaS multi-tenant?
- [ ] Apakah ini tetap dapat dijangkau pada infrastruktur terjangkau?

> **Cross-reference:** Jalur evolusi menuju skala enterprise diperinci pada §38 (Future Vision) dan §39 (Long-Term Strategy).

---

## 3. MISSION

DAYA Platform mengemban **tiga dimensi misi yang setara** — tidak ada yang lebih tinggi dari yang lain; ketiganya harus dipenuhi bersama.

### 3.1 Misi Bisnis
- Menyediakan kanal monetisasi yang **berkelanjutan** bagi creator: subscription, penjualan konten, affiliate, dan credit.
- Membangun *unit economics* yang sehat melalui **Revenue Model** dan **Revenue Sharing Engine** yang transparan.
- Menjamin **integritas finansial** melalui *Audit & Ledger Principles* (double-entry, append-only, immutable).

### 3.2 Misi Sosial
- Mengalirkan sebagian nilai transaksi ke misi sosial melalui **Mission & Foundation Model**.
- Menjamin **transparansi alokasi & dampak** melalui pelaporan publik (*Impact Measurement & Reporting*).
- **Memberdayakan creator kecil** agar memiliki akses setara terhadap alat monetisasi.

### 3.3 Misi Teknologi
- Membangun sistem **modular, aman, dan dapat dipelihara** menggunakan PHP Native + MySQL tanpa ketergantungan tooling berat.
- Menyediakan **Configuration Engine** yang membuat aturan bisnis dapat dikonfigurasi tanpa mengubah kode.
- Menjaga **kompatibilitas penuh dengan shared hosting** sambil menyediakan jalur evolusi ke arsitektur cloud/VPS.

```
        ┌──────────────────────────────────────────────┐
        │              MISI DAYA PLATFORM              │
        ├───────────────┬───────────────┬──────────────┤
        │  MISI BISNIS  │  MISI SOSIAL  │ MISI TEKNOLOGI│
        │  Monetisasi   │  Dampak yang  │  Modular,     │
        │  berkelanjutan│  terukur &    │  aman, ramah  │
        │  + integritas │  transparan   │  shared host  │
        │  finansial    │               │               │
        └───────────────┴───────────────┴──────────────┘
                  ketiganya SETARA & WAJIB
```

> **Cross-reference:** Penerjemahan misi sosial ke mekanisme dana → §30 (Revenue Sharing Philosophy) dan §29 (Business Rules).

---

## 4. CORE VALUES

Delapan nilai inti yang mengikat seluruh keputusan produk, teknis, dan tata kelola. Nilai bukan slogan — setiap nilai memiliki **makna operasional** yang dapat diuji.

| Nilai | Makna dalam Praktik |
|---|---|
| **Mission First** | Setiap keputusan ditimbang dampaknya terhadap misi sosial, bukan sekadar *vanity metrics*. |
| **Creator-Centric** | Kepentingan dan keadilan bagi creator diutamakan dalam desain fitur & pembagian nilai. |
| **Financial Integrity** | Tidak ada transaksi tanpa jejak. Setiap pergerakan dana wajib tercatat di ledger dan dapat diaudit. |
| **Transparency** | Alokasi misi, fee, dan komisi terbuka serta dapat diverifikasi. |
| **Simplicity over Complexity** | Solusi paling sederhana yang memenuhi kebutuhan selalu didahulukan. |
| **Security by Design** | Keamanan dipikirkan sejak desain, bukan ditambahkan di akhir. |
| **Maintainability** | Kode dan dokumen ditulis agar mudah dipahami orang/AI berikutnya. |
| **Inclusivity** | Platform dapat dijangkau dengan infrastruktur terjangkau. |

### 4.1 Cara Nilai Dipakai untuk Memutuskan

Ketika dua opsi bersaing, pilih opsi yang **lebih dekat** ke urutan nilai berikut, mengikuti *Trade-off Philosophy* proyek:

```
Financial Integrity  ▸  Security  ▸  Maintainability/Clarity  ▸  Performance  ▸  Delivery Speed
```

Contoh penerapan: bila menambah caching mempercepat performa tetapi mengaburkan kebenaran saldo, **tolak** — Financial Integrity mengalahkan Performance.

> **Cross-reference:** Nilai-nilai ini menjadi dasar Design Principles (§34) dan Governance Rules (§31).

---

## 5. BUSINESS PHILOSOPHY

Filosofi bisnis menjelaskan *cara berpikir fundamental* yang menjaga "rasa" produk tetap konsisten meski dikerjakan banyak orang dan banyak AI.

### 5.1 Tujuh Keyakinan Inti

| # | Prinsip | Penjelasan |
|:---:|---|---|
| 1 | **Mission over Metrics** | Pertumbuhan tanpa dampak misi bukanlah keberhasilan. |
| 2 | **Money is Sacred** | Modul finansial diperlakukan dengan kehati-hatian tertinggi; integritas mengalahkan kecepatan. |
| 3 | **Configurable, not Hardcoded** | Aturan bisnis yang dapat berubah (fee, komisi, % misi) harus dapat dikonfigurasi via *Configuration Engine*. |
| 4 | **Build for the Next Reader** | Kode & dokumen ditulis seakan akan dilanjutkan orang/AI yang belum pernah melihatnya. |
| 5 | **Constraint as a Feature** | Batasan shared hosting memaksa kesederhanaan, dan kesederhanaan adalah kekuatan. |
| 6 | **Trust is Earned by Transparency** | Kepercayaan creator dibangun dengan keterbukaan, bukan janji. |
| 7 | **Ship Small, Ship Safe** | Rilis kecil dan terverifikasi lebih baik daripada rilis besar yang berisiko. |

### 5.2 Implikasi Operasional

- **Money is Sacred** → setiap modul finansial wajib double-entry, idempotent, dan punya audit trail. Tidak ada "saldo yang dihitung ulang lalu disimpan langsung".
- **Configurable, not Hardcoded** → nilai seperti persentase misi atau komisi affiliate **tidak boleh** ditanam di kode; harus melewati Configuration Engine.
- **Ship Small, Ship Safe** → mendukung pola *vertical slice* (membuktikan satu domain secara utuh sebelum menskalakan ke modul lain).

> **Catatan status.** *Configuration Engine* (#33) dan *Audit & Ledger Principles* (#34) berstatus dokumen fondasi pada Master Blueprint; sebagiannya belum terimplementasi di kode. Filosofi ini tetap mengikat keputusan ke depan.

> **Cross-reference:** Terjemahan filosofi ke aturan konkret → §29 (Business Rules), §30 (Revenue Sharing Philosophy). Terjemahan ke arsitektur → §10, §34, §35.

---

## 6. CREATOR ECONOMY PHILOSOPHY

Bagian ini menetapkan cara DAYA memandang **ekonomi kreator** sebagai inti nilai platform.

### 6.1 Prinsip Identitas & Kepemilikan

- **User sebagai identitas dasar.** `Creator`, `Audience`, dan `Affiliate` adalah **role specialization** dari `User`, bukan identitas terpisah. Satu User dapat memegang beberapa role sekaligus.
- **Hanya creator sah & aktif** yang dapat mempublikasikan karya dan menerima pendapatan. Onboarding creator melewati alur aplikasi → review → persetujuan (terlihat pada entity `Creator Application` dan tabel `creator_applications`).
- **Karya dimiliki creator, bukan user biasa.** Kepemilikan karya diikat ke `creator_id`.

### 6.2 Content sebagai Poros Nilai

Keputusan arsitektur permanen (dari `CONTENT_ARCHITECTURE_DECISION.md`):

- **`Content` adalah satu-satunya aggregate root** untuk seluruh karya — metadata, lifecycle publikasi, model akses, SEO, dan identitas publik.
- **Jenis karya = `content_type`** (Story, Novel, Cerpen, Artikel, Audio, Podcast, Ebook, dst.), **bukan** modul atau tabel terpisah per jenis. Menambah tipe baru tidak membuat modul baru.
- **Semua fitur turunan mengacu ke `content_id`**: SEO, Analytics, Views, Likes, Comments, Shares, Wallet Revenue, Sponsor, Donation, Affiliate, Recommendation, Search.

```
   Creator ──owns──▶ Content ──grouped by──▶ Collection
                       │                         │
                       └── Content Part          └── CollectionItem
                       (sub-unit terurut,        (referensi terurut
                        bab/episode)              ke Content)

   Semua monetisasi & analitik berporos pada content_id.
```

### 6.3 Progresi & Dampak Creator

- **CreatorTier** — tingkat creator (mis. Starter / Pro / Elite), bersifat *immutable* dan ditentukan kriteria.
- **MissionScore** — skor kontribusi dampak misi seorang creator.
- **CreatorHandle** — pengenal publik unik (slug) untuk profil & URL SEO-friendly.

### 6.4 Pemisahan Tanggung Jawab Domain

| Domain | Memegang | TIDAK memegang |
|---|---|---|
| Creator | Identitas profesional, tier, mission score, hak monetisasi | Kebenaran finansial, isi karya |
| Content | Metadata & aturan akses karya | Penyimpanan file fisik, pencatatan uang |
| Wallet *(terencana)* | Kebenaran finansial (ledger) | Identitas creator, isi karya |

> **Cross-reference:** Model domain lengkap → `DAYA_PLATFORM_DEVELOPER_PACK/DOMAIN_MODEL.md`. Mekanisme bagi hasil → §30. Aturan bisnis → §29.

---

## 7. BUSINESS MODEL

> **Status.** Dokumen *Business Model Canvas* formal (#3, kode `BMODEL`) tercatat pada Master Blueprint sebagai dokumen Fase 0 yang **belum final**. Bagian ini merangkum model bisnis **berdasarkan fakta yang sudah ada di repository** dan secara eksplisit menandai hal yang masih menunggu finalisasi, agar tidak menjadi asumsi.

### 7.1 Tiga Aliran Nilai

| Dimensi | Mekanisme di DAYA |
|---|---|
| **Value Creation** (menciptakan nilai) | Creator memproduksi `Content`; platform menyediakan distribusi, monetisasi, dan kepercayaan (transparansi + audit). |
| **Value Delivery** (memberikan nilai) | Audiens mengakses karya (gratis / berbayar / membership); affiliate menyebarkan jangkauan; misi sosial menerima alokasi dana. |
| **Value Capture** (menangkap nilai) | Platform fee, komisi affiliate, dan alokasi misi diambil dari aliran transaksi — semuanya tercatat di ledger. |

### 7.2 Kanal Monetisasi Creator

Empat kanal yang menjadi acuan misi bisnis (§3.1):

1. **Subscription** — langganan ke creator/konten.
2. **Penjualan Konten** — pembelian karya berbayar (model akses per `Content`).
3. **Affiliate** — komisi atas rujukan/penjualan.
4. **Credit** — sistem kredit internal pada Wallet.

### 7.3 Segmen Pengguna

| Segmen | Peran Bisnis |
|---|---|
| **Creator** | Produsen nilai; menerima pendapatan. |
| **Member / Audiens** | Konsumen karya; sumber permintaan. |
| **Affiliate** | Penggerak distribusi; menerima komisi. |
| **Admin / Super Admin** | Operasi & tata kelola platform. |
| **Mission / Foundation Stakeholder** | Penerima & pengawas alokasi misi. |

### 7.4 Prinsip Penangkapan Nilai yang Mengikat

- **Uang disimpan sebagai integer minor unit (BIGINT)** di seluruh sistem — tanpa floating point untuk dana.
- **Ledger adalah satu-satunya sumber kebenaran finansial** — saldo diturunkan dari ledger, tidak pernah dimutasi langsung.
- **Foundation menerima nilai hanya** melalui *Revenue Sharing* atau *Sponsor* (entity governance, bukan pelaku transaksi langsung).
- **Parameter ekonomi dapat dikonfigurasi** (fee, komisi, % misi) — bukan hardcoded.

> **Belum final / menunggu dokumen sumber:** struktur biaya rinci & *unit economics* (#3), strategi produk & positioning (#4 `PSTRAT`), Revenue Model rinci (#7), dan Revenue Sharing Engine (#31). PROJECT_BIBLE akan merujuk dokumen-dokumen ini begitu disahkan, bukan menebak angkanya.

> **Cross-reference:** Filosofi bagi hasil → §30. Aturan main finansial → §29. Risiko bisnis (mis. fraud affiliate, ketidakpercayaan alokasi misi) → §32.

---

## 8. PLATFORM SCOPE

Bagian ini memisahkan dengan tegas **ruang lingkup yang ditargetkan (V1)** dari **realita yang sudah terimplementasi**, sesuai prinsip Source of Truth.

### 8.1 Ruang Lingkup V1 (Targeted)

Cakupan V1 menurut Project Constitution: manajemen akun & peran, creator economy, content engine, wallet & credit, payment, revenue sharing, affiliate engine, mission allocation, admin panel, analytics, dan notification. Ruang lingkup final tunduk pada dokumen **MVP Scope (#29)** dan **Non-Goals V1 (#30)**.

| Domain V1 | Status Dokumen | Status Kode (acuan utama) |
|---|:---:|:---:|
| Authentication & RBAC | — | ✅ Terimplementasi |
| Creator | 🟡 Domain | ✅ Terimplementasi |
| Content | 🟡 Domain | ✅ Terimplementasi |
| Collection | — | ✅ Terimplementasi |
| Wallet & Credit | ✅ Pilot (docs) | 🔴 Belum berkode |
| Payment (Duitku) | 🔴 Draft | 🔴 Belum berkode |
| Affiliate Engine | 🔴 Draft | 🔴 Belum berkode |
| Revenue Sharing | 🔴 Draft | 🔴 Belum berkode |
| Mission / Foundation | 🔴 Draft | 🔴 Belum berkode |
| Notification | 🔴 Draft | 🔴 Belum berkode |
| Analytics | 🔴 Draft | 🔴 Belum berkode |
| Admin Panel | 🔴 Draft | 🟡 Sebagian (monitoring per modul) |

> Tabel ini menegaskan rekonsiliasi: status **dokumen** dan status **kode** dapat berbeda; yang berlaku untuk pengambilan keputusan adalah **kolom Status Kode**.

### 8.2 Target Platform & Lingkungan

- **Target platform:** Web (mobile-first, responsive) + **REST API ready** untuk konsumsi mobile/eksternal.
- **Lingkungan deployment:** Shared hosting via cPanel / FastPanel.
- **Constraint operasional resmi:** Dilarang menjadikan **Docker, NodeJS, SSH, atau Terminal** sebagai prasyarat operasional. Tooling tersebut hanya boleh opsional di lingkungan pengembangan lokal.

### 8.3 Di Luar Lingkup (Indikatif)

> Daftar *Non-Goals V1* final mengikuti dokumen #30. Secara indikatif berdasarkan arah arsitektur, hal berikut **bukan** target V1: multi-tenancy penuh, infrastruktur cloud/VPS dengan queue worker wajib, dan ketergantungan build pipeline (NodeJS/bundler). Jalur menuju kapabilitas tersebut dibahas sebagai evolusi pada Bagian VII.

> **Cross-reference:** Urutan implementasi modul → `TRAE_PROJECT_KIT/IMPLEMENTATION_SEQUENCE.md`. Evolusi V1→V5 → §37–§39.

---
---

> **— Akhir Bagian I —**

---
---

# BAGIAN II — ENGINEERING

> Bagian ini menetapkan *bagaimana* DAYA Platform dibangun: stack, arsitektur, struktur folder, filosofi database, dan standar penulisan kode, modul, keamanan, performa, serta UI/UX. Seluruh standar di sini berlaku untuk kode dan bersumber dari repository (`docs/` + implementasi nyata di `app/`).

---

## 9. TECHNOLOGY STACK

### 9.1 Stack Wajib

| Lapisan | Teknologi | Catatan Mengikat |
|---|---|---|
| **Backend** | PHP Native 8.x | Tanpa framework berat; modular monolith. |
| **Database** | MySQL (InnoDB) | Transaksi DB wajib untuk modul finansial; `utf8mb4`. |
| **UI** | Bootstrap 5 + Vanilla JS | Mobile-first, tanpa build tool/bundler. |
| **Deployment** | cPanel / FastPanel | File Manager + phpMyAdmin + cron. |
| **VCS** | GitHub | Source of Truth (branch `main`). |

### 9.2 Teknologi Terlarang sebagai Prasyarat

| Terlarang | Alasan |
|---|---|
| **Docker** | Tidak tersedia/diizinkan di shared hosting target. |
| **NodeJS** | Menghindari build pipeline wajib; UI tanpa bundler. |
| **SSH** | Operasi dilakukan via cPanel/File Manager. |
| **Terminal** | Deployment & migrasi via panel + phpMyAdmin. |

> Tooling di atas **boleh** dipakai opsional di lingkungan pengembangan lokal, **tidak pernah** menjadi syarat deployment produksi.

### 9.3 Versi & Kompatibilitas

- PHP `declare(strict_types=1)` dipakai di seluruh kode core (terverifikasi pada `app/core/Application.php`, `app/config/bootstrap.php`).
- Hindari sintaks SQL yang belum dipastikan tersedia di MySQL versi shared hosting; migrasi harus aman di-*import* manual via phpMyAdmin.

> **Cross-reference:** Alasan pemilihan stack didokumentasikan sebagai keputusan di §35 (ADR). Filosofi database di §12.

---

## 10. ARCHITECTURE PHILOSOPHY

### 10.1 Prinsip Inti

| # | Prinsip | Penjelasan |
|:---:|---|---|
| 1 | **Modular Monolith** | Satu basis kode terorganisir menjadi modul independen — bukan microservices — demi kesesuaian shared hosting. |
| 2 | **Separation of Concerns** | Pemisahan tegas: routing, business logic, data access, presentation. |
| 3 | **Thin Controller, Rich Service** | Controller hanya mengatur alur HTTP; logika bisnis di service layer. |
| 4 | **Stateless Request, Stateful Ledger** | Request stateless; satu-satunya kebenaran finansial adalah ledger di database. |
| 5 | **Configuration-Driven** | Perilaku dikendalikan Configuration Engine, bukan nilai tersebar di kode. |
| 6 | **Front Controller** | Semua request masuk via `public/index.php` → bootstrap → router → middleware. |
| 7 | **Module Auto-Discovery** | `ModuleManager` memuat config, route, dan lifecycle modul berdasarkan konvensi. |
| 8 | **Shared Hosting First** | Aman tanpa Docker, queue worker wajib, SSH, atau bundler. |

### 10.2 Request Lifecycle (Terverifikasi di Kode)

Alur nyata dari `app/config/bootstrap.php`:

```
HTTP Request
   │
public/index.php  (web root tunggal)
   │
app/config/bootstrap.php
   ├─ Autoloader.register()  (App\Core, App\Middleware, App\Modules)
   ├─ Env::load() + Config::load(app, database)
   ├─ ModuleManager.loadConfigurations()
   ├─ Logger + ErrorHandler::register()
   ├─ SessionManager.start()
   ├─ Router + Application
   ├─ require routes.php  (route global)
   ├─ Request::capture() + setSession()
   ├─ ModuleManager.bootModules()      → boot tiap modul + registerRoutes()
   ├─ Guard: app.installed? → redirect /install bila belum
   │
   ▼
Router.dispatch(Request)
   │
Middleware Pipeline  [Auth] → [RBAC] → [CSRF] → [RateLimit]
   │
Controller (thin)  →  Service (rich)  →  Repository (PDO prepared)  →  MySQL (InnoDB)
   │
Response  (html / json / redirect)
```

### 10.3 Base Layer Reusable

Seluruh modul mewarisi lapisan dasar `App\Core\Modular\*`:

`BaseModule` · `BaseController` · `BaseService` · `BaseRepository` · `BaseModel` · `BaseRequest` · `BaseResponse`.

### 10.4 Keputusan Arsitektur Permanen

Selain 10 keputusan baseline (lihat `docs/ARCHITECTURE_DECISION.md`, dirangkum di §35), berlaku keputusan domain dari `docs/CONTENT_ARCHITECTURE_DECISION.md`: **Content sebagai aggregate root tunggal seluruh karya** dengan `content_type` sebagai pembeda jenis. Modul lain (Wallet, Payment, Sponsor, Foundation, Analytics, Notification, Affiliate) **wajib** merujuk `content_id` saat berhubungan dengan karya.

> **Cross-reference:** Pemetaan ke struktur folder → §11. Prinsip desain (SOLID/SoC/DRY/KISS) → §34. Rekam keputusan → §35.

---

## 11. FOLDER STRUCTURE

### 11.1 Struktur Proyek (Acuan Repository)

Hanya `public/` yang terekspos web. Kode aplikasi berada di luar web root.

```
daya-platform/
├── public/                  # Web root (SATU-SATUNYA yang terekspos)
│   ├── index.php            # Front controller
│   ├── .htaccess            # Routing & security
│   └── assets/              # css, js, img (Bootstrap 5, vanilla JS)
├── app/
│   ├── config/              # bootstrap.php, app.php, database.php, routes.php
│   ├── core/                # Application, Router, ModuleManager, Autoloader,
│   │   │                    # Config, Env, ErrorHandler, Http/*, Logging/*
│   │   └── Modular/         # BaseModule/Controller/Service/Repository/Model/Request/Response
│   ├── middleware/          # Auth, RBAC, CSRF, RateLimit (global)
│   ├── modules/             # Modul independen (lihat 11.2)
│   └── helpers/             # functions.php (utilitas reusable)
├── storage/                 # Di luar web root
│   ├── logs/
│   ├── uploads/
│   ├── cache/
│   └── config/.env          # rahasia di luar web root
├── database/
│   ├── migrations/          # SQL bernomor timestamp (phpMyAdmin-friendly)
│   └── seeders/             # seed data (mis. permission)
├── docs/                    # Dokumentasi baseline & PROJECT_BIBLE.md
└── README.md
```

### 11.2 Struktur Internal Modul

Setiap modul `app/modules/<ModuleName>/` bersifat *self-contained* dengan struktur konsisten (lihat `MODULE_TEMPLATE.md`):

```
app/modules/<ModuleName>/
├── <ModuleName>Module.php   # root lifecycle, mewarisi BaseModule
├── routes.php               # route modul (di-register otomatis ModuleManager)
├── config/module.php        # konfigurasi modul
├── controllers/             # thin — Abstract<ModuleName>Controller + turunannya
├── services/                # rich — business logic + ModuleBootstrap
├── models/                  # entity + repository (BaseRepository)
├── requests/                # wrapper input (BaseRequest)
├── responses/               # wrapper output (BaseResponse)
├── middleware/              # middleware spesifik modul (opsional)
├── policies/                # otorisasi (mis. pada modul Collection)
├── resources/               # transform/representasi output (opsional)
├── views/                   # admin/ · public/ · partials/
└── assets/                  # css/ · js/ · img/ (opsional)
```

### 11.3 Aturan Folder

- [ ] Kode aplikasi **tidak boleh** berada di dalam `public/`.
- [ ] Konfigurasi & rahasia **di luar** web root (`storage/config/.env`).
- [ ] Nama folder modul **identik** dengan nama class module utama (mis. folder `Creator` ⇒ `App\Modules\Creator\CreatorModule`).
- [ ] Setiap modul mengikuti struktur internal yang sama (konsistensi).

> **Cross-reference:** Konvensi penamaan detail → §13. Standar modul & dokumen wajib modul → §14.

---

## 12. DATABASE PHILOSOPHY

### 12.1 Prinsip Mengikat

| Prinsip | Aturan |
|---|---|
| **Engine** | InnoDB untuk seluruh tabel (dukungan transaksi & FK). |
| **Charset** | `utf8mb4` `utf8mb4_unicode_ci`. |
| **Uang** | Disimpan sebagai **integer minor unit (BIGINT)** — tanpa floating point. |
| **Ledger** | **Append-only, immutable, double-entry.** Saldo **diturunkan** dari ledger, tidak pernah dimutasi langsung. |
| **Integritas** | Foreign Key eksplisit (`ON DELETE CASCADE`/`SET NULL` sesuai kebutuhan audit) + transaksi DB. |
| **Akses** | **Selalu** prepared statements (PDO) — dilarang konkatenasi string SQL. |
| **Token** | Token sensitif (verifikasi, reset, remember) disimpan sebagai **hash**, bukan raw. |

### 12.2 Konvensi Skema (Terverifikasi di Migrasi)

Contoh nyata dari `database/migrations/` (Authentication, Creator, Collection):

- Primary key `id BIGINT UNSIGNED AUTO_INCREMENT`.
- Foreign key bernama `<entitas>_id` dengan constraint `fk_...` eksplisit.
- Kolom audit waktu `created_at`, `updated_at` (dan `deleted_at` untuk soft delete pada Collection).
- Indeks selektif (`idx_...`) pada kolom status, FK, dan kolom pencarian; `UNIQUE KEY` (`uq_...`) untuk slug/email/uuid/token.
- Enum status terkontrol (mis. `users.status`, `creator_applications.status`).

### 12.3 Strategi Migrasi (cPanel-Friendly)

```
1. Simpan migrasi di database/migrations/
2. Nama file: <timestamp>_<deskripsi>.sql
   contoh: 20260628_000001_create_authentication_module.sql
3. SQL aman di-import manual via phpMyAdmin (tanpa CLI)
4. Hindari sintaks bergantung versi DB modern yang belum dipastikan tersedia
5. Update DATABASE.md modul SEBELUM migrasi dianggap final
6. Jalankan migrasi berurutan sesuai dependency antar modul
```

Urutan deploy nyata (contoh Collection): jalankan migrasi Authentication → Creator → Content → Collection, lalu seeder permission terkait.

### 12.4 Soft Delete & Retensi

- Modul yang membutuhkan pemulihan memakai kolom `deleted_at` (pola pada Collection: `idx_..._deleted_at`).
- Kebijakan retensi & arsip formal mengikuti **Database Blueprint (#16)** yang masih berstatus rencana pada Master Blueprint.

> **Cross-reference:** Prinsip Audit & Ledger lengkap → §29–§30 dan dokumen #34. NFR backup/disaster recovery → §36.

---

## 13. CODING STANDARD

> Standar kanonik adalah `docs/CODING_STANDARD.md`. Bila konflik dengan dokumen lain, ikuti `PROJECT_CONSTITUTION.md` lalu `CODING_STANDARD.md`.

### 13.1 Naming Convention

| Elemen | Konvensi | Contoh |
|---|---|---|
| Class | `PascalCase` | `CreatorService` |
| File class | Sama dengan class | `CreatorService.php` |
| Method | `camelCase` | `createCreatorProfile()` |
| Variabel | `camelCase` | `$creatorId` |
| Konstanta | `UPPER_SNAKE_CASE` | `DEFAULT_PAGE_SIZE` |
| Tabel DB | `snake_case` jamak | `creator_profiles` |
| Kolom DB | `snake_case` | `email_verified_at` |
| Primary Key | `id` | `id` |
| Foreign Key | `<entitas>_id` | `creator_id` |
| Route/URL | `kebab-case` | `/creator-dashboard` |
| View file | `snake_case` | `profile_card.php` |
| Migration | `snake_case` + timestamp | `20260628_000001_create_authentication_module.sql` |

### 13.2 Namespace Convention

- Root aplikasi: `App\`
- Core framework: `App\Core\...`
- Base modular: `App\Core\Modular\...`
- Middleware global: `App\Middleware\...`
- Modul: `App\Modules\<ModuleName>\...`

### 13.3 Layering Convention

| Lapisan | Boleh | Tidak Boleh |
|---|---|---|
| **Controller** | Terima `Request`, panggil request wrapper + service, kembalikan `Response`. | Memuat business logic atau SQL. |
| **Service** | Business logic & orchestration; dependency eksplisit via constructor. | Mengakses `$_POST/$_GET/$_COOKIE/$_SESSION` langsung; merender view. |
| **Repository** | Persistensi & query; **wajib** prepared statements. | Memuat business rule kompleks. |
| **Model** | Representasi entity/domain object. | Menyimpan logic akses database. |

### 13.4 Clean Code

- [ ] Satu fungsi melakukan satu hal (*Single Responsibility*).
- [ ] Nama menjelaskan maksud tanpa perlu komentar.
- [ ] Tanpa *magic number* — gunakan konstanta/konfigurasi.
- [ ] Komentar menjelaskan **mengapa**, bukan **apa**.
- [ ] DRY — logika dipakai >1 kali diangkat menjadi helper/service; komponen UI berulang → partial view.

> **Cross-reference:** Larangan praktik (SQL di controller, akses internal modul lain) → §14.3. Prinsip SOLID/DRY/KISS → §34.

---

## 14. MODULE STANDARD

### 14.1 Kontrak Auto-Discovery

Modul baru **tidak** perlu wiring manual selama memenuhi konvensi berikut (dimuat otomatis `ModuleManager`):

- [ ] Folder `app/modules/<ModuleName>/`
- [ ] Class utama `<ModuleName>Module` mewarisi `BaseModule`
- [ ] Namespace `App\Modules\<ModuleName>\<ModuleName>Module`
- [ ] `routes.php` di root modul
- [ ] `config/module.php` di modul

### 14.2 Dokumen Wajib per Modul

Setiap modul menyertakan suite dokumen (mengikuti `MODULE_TEMPLATE.md`):

`README.md` · `BUSINESS_RULES.md` · `FLOW.md` · `DATABASE.md` · `API.md` · `UI.md` · `DEVELOPMENT_NOTES.md` · `TESTING_CHECKLIST.md` · `DEPLOYMENT_NOTES.md`

### 14.3 Larangan Modul (Boundary)

| ⛔ Dilarang | Alasan |
|---|---|
| Business logic di route | Route hanya pengarah; logic di service. |
| SQL di controller | Pelanggaran SoC; SQL milik repository. |
| Mengakses file internal modul lain langsung | Komunikasi antar modul **wajib** lewat service/interface dengan boundary jelas. |
| Memperkenalkan identitas karya paralel di luar `Content` | Melanggar keputusan arsitektur Content sebagai aggregate root. |
| Menambah dependency yang mewajibkan Docker/NodeJS/SSH | Merusak shared hosting compatibility. |
| Menyimpan rahasia ke repository | Pelanggaran keamanan. |

### 14.4 Status Modul & Gerbang Implementasi

Modul hanya boleh diimplementasi setelah dokumennya lengkap dan disetujui. Legenda status implementasi: ✅ READY · 🟡 DOMAIN READY · 🔴 DRAFT. **Modul ber-status 🟡/🔴 dilarang diimplementasi** sebelum dilengkapi.

> **Catatan rekonsiliasi (Source of Truth).** Status dokumen di Developer Pack dapat berbeda dari kenyataan kode. Yang berlaku untuk keputusan adalah **status kode** — lihat Lampiran B. Modul `Authentication`, `Creator`, `Content`, `Collection` sudah ✅ terimplementasi di kode meski sebagian dokumen pack masih menandainya 🟡/🔴.

> **Cross-reference:** Template lengkap → `MODULE_TEMPLATE.md`. Urutan implementasi 14 phase → `TRAE_PROJECT_KIT/IMPLEMENTATION_SEQUENCE.md`.

---

## 15. SECURITY STANDARD

> Standar kanonik: `SECURITY_STANDARD.md` (Developer Pack) + praktik nyata pada modul Authentication.

### 15.1 Kontrol Wajib

| Area | Aturan |
|---|---|
| **Input** | Validasi & sanitasi **server-side wajib** (client-side hanya pelengkap). |
| **SQL** | PDO prepared statements; dilarang konkatenasi string SQL. |
| **CSRF** | Token CSRF pada **semua** form yang mengubah state (terlihat aktif di `routes.php`). |
| **Password** | Hash dengan `password_hash()` (bcrypt/argon2). |
| **Token** | Disimpan sebagai hash di DB (verifikasi/reset/remember). |
| **Otorisasi** | RBAC dengan prinsip *least privilege* (tabel `roles`, `permissions`, pivot). |
| **Rahasia** | API key/kredensial **di luar** web root; **tidak pernah** masuk repository. |
| **Sesi** | `SessionManager` terpusat; dukungan regenerate session. |
| **Rate Limit** | `RateLimitMiddleware` pada endpoint sensitif. |
| **Audit** | Logging & audit trail untuk aksi penting (mis. `login_history`). |

### 15.2 OWASP Top 10

Setiap fitur wajib melewati checklist mitigasi OWASP Top 10 (injection, broken auth, XSS, dsb.). Detail penuh mengikuti **Security Architecture (#20)** yang berstatus rencana.

### 15.3 Kewajiban Tiap Fitur

- [ ] Validasi input (server-side)
- [ ] Logging
- [ ] Audit trail
- [ ] Kontrol keamanan (authn/authz sesuai konteks)

> **Cross-reference:** Modul finansial menambah idempotency + double-entry (§12, §29). Compliance → §33. Audit logging sebagai NFR → §36.

---

## 16. PERFORMANCE STANDARD

> Dokumen *Non-Functional Requirements (#14)* dan *Caching & Performance Strategy (#17)* berstatus rencana pada Master Blueprint. Bagian ini menetapkan prinsip yang **sudah** dapat ditegakkan pada konteks shared hosting; angka target formal mengikuti dokumen NFR saat disahkan (lihat §36).

### 16.1 Prinsip Performa pada Shared Hosting

| Prinsip | Penerapan |
|---|---|
| **Index-aware queries** | Manfaatkan indeks yang sudah ada (`idx_...`); hindari full-table scan pada kolom status/FK. |
| **Hindari N+1** | Repository mengembalikan data secukupnya; agregasi di query, bukan loop. |
| **Caching ringan** | Gunakan `storage/cache/` untuk data jarang berubah (mis. config, reference data) — tanpa dependency eksternal wajib. |
| **Stateless request** | Tidak menyimpan state mahal antar-request; sesuai prinsip arsitektur §10. |
| **Asset tanpa build** | Bootstrap 5 + vanilla JS; hindari pipeline berat yang membebani waktu deploy. |
| **Pagination** | Daftar panjang wajib paginasi (mis. directory publik Collection). |

### 16.2 Performa Modul Finansial

Optimasi **tidak boleh** mengorbankan integritas finansial. Caching saldo yang menggantikan derivasi dari ledger **dilarang** — saldo selalu diturunkan dari ledger (§12.1).

### 16.3 Heuristik Trade-off

Sesuai urutan resmi proyek: **Integritas Finansial → Keamanan → Maintainability → Performa → Delivery**. Performa hanya dioptimasi setelah tiga prioritas di atasnya aman.

> **Cross-reference:** Indexing strategy detail → Database Blueprint (#16). Target & metrik NFR → §36.

---

## 17. UI / UX STANDARD

> Standar kanonik: `UI_STANDARD.md` (Developer Pack). Prinsip desain tingkat tinggi (induk) mengikuti Design Principles (#28).

### 17.1 Prinsip UI Wajib

| Prinsip | Aturan |
|---|---|
| **Mobile-First** | Desain dimulai dari layar kecil, lalu *scale up*. |
| **Bootstrap 5** | Komponen & grid standar; tanpa build tool. |
| **Responsive** | Berfungsi baik di mobile hingga desktop. |
| **SEO-Friendly** | Markup semantik; URL publik memakai `slug` Content. |
| **Aksesibilitas** | Struktur aksesibel (label, kontras, navigasi keyboard). |
| **State eksplisit** | Status **loading / empty / error** ditampilkan jelas. |
| **Money display** | Nilai uang dikonversi dari **minor unit** ke format tampilan saat render. |
| **Komponen reusable** | UI berulang dibuat sebagai partial view (`views/partials/`). |

### 17.2 Struktur View per Modul

```
views/
├── admin/      # layar admin/monitoring
├── public/     # layar publik (SEO-friendly)
└── partials/   # komponen reusable
```

### 17.3 Checklist UI Sebelum Selesai

- [ ] Responsif di mobile (uji viewport kecil)
- [ ] State loading/empty/error tersedia
- [ ] Nilai uang ditampilkan dari minor unit dengan benar
- [ ] Markup SEO-friendly (untuk halaman publik)
- [ ] Tidak ada dependency build pipeline wajib

> **Cross-reference:** Design Principles & Experience Pillars → §34 dan dokumen #28. Admin Panel detail → #21 (rencana).

---
---

> **— Akhir Bagian II —**

---
---

# BAGIAN III — DEVELOPMENT

> Bagian ini menetapkan *cara kerja sehari-hari* DAYA Platform Enterprise: dari Git hingga produksi. Seluruh alur di bawah menggambarkan workflow nyata proyek. Aturan yang **belum** ada di repository ditandai **`Planned Standard`** dan tidak boleh dianggap sudah berlaku.
>
> **Legenda sumber pada Bagian ini:**
> `Repo` = sudah tertera di repository (kode/dokumen) · `Planned Standard` = usulan yang belum disahkan/diimplementasi.

---

## 18. GIT WORKFLOW

Mengatur Git, branch, commit, pull request, dan code review. GitHub adalah **Source of Truth** (branch `main`).

### 18.1 Branch Strategy `Repo`

Model nyata: `main` (stabil) ← `develop` (integrasi) ← `feature/*` (pengerjaan). **Tidak ada commit langsung ke `main`.**

```
   feature/wallet-ledger ──┐
   feature/creator-tier ───┤   (satu fitur = satu branch)
   feature/content-slug ───┤
                           ▼
                       develop          (integrasi & uji gabungan)
                           │
                           ▼  (lewat PR + review + DoD)
                         main           (STABIL = Source of Truth)
                           │
                           ▼  (tag rilis SemVer)
                       v1.0.0, v1.1.0…
```

| Branch | Tujuan | Aturan |
|---|---|---|
| `main` | Kode stabil & rilis | Hanya via PR tervalidasi; protected; tidak ada commit langsung. |
| `develop` | Integrasi antar fitur | Tujuan merge `feature/*`; basis pembuatan branch fitur. |
| `feature/<modul>-<ringkas>` | Satu fitur/perbaikan | Dibuat dari `develop`; dihapus setelah merge. |
| `hotfix/<ringkas>` | Perbaikan darurat produksi | `Planned Standard` — dari `main`, di-merge balik ke `main` & `develop`. |

### 18.2 Commit Convention

**`Repo`:** commit wajib **jelas & bermakna**; rahasia tidak pernah masuk repo.

**`Planned Standard`** (usulan format terstruktur, belum disahkan): mengadopsi gaya *Conventional Commits* agar Change Log dapat diturunkan otomatis.

```
<type>(<scope>): <ringkasan imperatif>

Contoh:
feat(wallet): tambah double-entry ledger posting
fix(creator): perbaiki validasi handle unik
docs(bible): tambah Bagian III Development

type   : feat | fix | docs | refactor | test | chore | perf | security
scope  : nama modul/area (wallet, creator, content, bible, ...)
```

> Sampai format ini disahkan, aturan yang **berlaku** hanyalah "commit jelas & bermakna" (`Repo`).

### 18.3 Pull Request Workflow `Repo`

Satu PR per fitur; setiap PR **wajib merujuk Business Rules / dokumen terkait**.

```
feature/* siap
   │
   ├─ Jalankan CHECKLIST/PRE_COMMIT (§18.6)
   │
   ▼
Buka Pull Request → target: develop
   ├─ Deskripsi: apa, mengapa, dokumen/Business Rules terkait
   ├─ Tautkan modul & file dokumen yang diperbarui
   │
   ▼
Code Review (§18.4)
   │
   ├─ Menyentuh modul finansial/keamanan? ──► Review Ketat
   │
   ▼
Approval → Merge ke develop → (akhirnya) PR develop→main
```

### 18.4 Code Review Workflow

Diturunkan dari Change Management proyek (`Repo`): perubahan yang menyentuh **modul finansial/keamanan** menjalani **Review Ketat (Architect + Security)**; selain itu **Review Standar**.

```
        ┌───────────────┐
        │  PR diajukan  │
        └──────┬────────┘
               ▼
   Menyentuh finansial / keamanan?
        │ Tidak          │ Ya
        ▼                ▼
  Review Standar   Review Ketat (Architect + Security)
        │                │
        └───────┬────────┘
                ▼
         Lolos kriteria review?
            │ Tidak → revisi (kembali ke feature/*)
            │ Ya
            ▼
          Approval → Merge
```

**Kriteria review (`Planned Standard` — checklist formal belum ada di repo, diturunkan dari standar yang berlaku):**

- [ ] Mengikuti `CODING_STANDARD` & `FOLDER_STRUCTURE`
- [ ] Thin controller / rich service; SQL hanya di repository
- [ ] Prepared statements; tidak ada konkatenasi SQL
- [ ] Tidak ada rahasia/hardcoded value konfigurabel
- [ ] Sesuai Business Rules modul; dokumen terkait diperbarui
- [ ] Modul finansial: double-entry, idempotent, audit trail

### 18.5 Aturan Mutlak Git `Repo`

- ⛔ Tanpa commit langsung ke `main`.
- ⛔ Rahasia/kredensial tidak pernah masuk repository.
- ✅ Satu PR per fitur, merujuk dokumen terkait.
- ✅ Setiap perubahan tercatat (commit + PR + Change Log).

### 18.6 Checklist Implementasi — Git `Repo`

- [ ] Branch dibuat dari `develop` dengan pola `feature/<modul>-<ringkas>`
- [ ] Commit jelas & bermakna (idealnya format §18.2 bila sudah disahkan)
- [ ] `CHECKLIST/PRE_COMMIT` dijalankan sebelum PR
- [ ] PR merujuk Business Rules / dokumen terkait
- [ ] Review sesuai jalur (Standar / Ketat)
- [ ] Tidak ada rahasia di diff

> **Cross-reference:** Coding standard → §13. Change Management → §G.6 & §23.3. Versioning/tag rilis → §23.2.

---

## 19. DEVELOPMENT WORKFLOW

Alur resmi dari ide hingga pemeliharaan, beserta gerbang kualitas.

### 19.1 Alur Ide → Maintenance `Repo`

```
 Idea ─► Blueprint ─► Business Rules ─► Database Design ─► Architecture
   ▲                                                          │
   │                                                          ▼
 (umpan balik)                                              UI / UX
   │                                                          │
   │                                                          ▼
 Maintenance ◄─ Deployment ◄─ {DoD?} ◄─ Testing ◄─ TRAE Dev ◄─ Nexla Generate
                                 │ Tidak → kembali ke TRAE Dev
                                 │ Ya → lanjut Deployment
```

| Tahap | Penanggung Jawab | Output |
|---|---|---|
| Idea | ChatGPT + Owner | Konsep & justifikasi |
| Blueprint | Claude | Dokumen blueprint bagian terkait |
| Business Rules | Claude | Business Rules Catalog (BR-xxx) |
| Database Design | Claude (analisis) | ERD & Data Dictionary |
| Architecture | ChatGPT + Claude | Architecture spec & ADR |
| UI/UX | Claude (spesifikasi) | Wireframe & design system |
| Generate | Nexla | Scaffolding, CRUD, dashboard |
| Development | TRAE | Kode fitur final |
| Testing | QA | Hasil uji & laporan keamanan |
| Deployment | Owner/DevOps | Rilis di shared hosting |
| Maintenance | Tim | Patch, monitoring, evolusi |

> **Catatan Source of Truth.** Peran AI di atas berlaku untuk pekerjaan *baru*. Untuk modul yang **sudah berkode** (Authentication, Creator, Content, Collection), kebenaran perilaku adalah kode itu sendiri (lihat Lampiran B).

### 19.2 Gerbang Dokumentasi (Hard Gate) `Repo`

Prinsip non-negotiable proyek: **dokumentasi modul wajib lengkap sebelum implementasi.** Modul ber-status 🟡/🔴 **dilarang** dikodekan sebelum dilengkapi mengikuti `MODULE_TEMPLATE.md` dan disetujui.

### 19.3 Definition of Ready (DoR)

> **`Planned Standard`** — artefak DoR formal belum ada di repository. Disusun di sini dari gerbang dokumentasi yang **sudah** berlaku (`Repo`) agar dapat segera dipakai dan disahkan.

Sebuah pekerjaan modul **Ready** untuk dikodekan bila:

- [ ] Dokumen modul lengkap (✅ READY) sesuai `MODULE_TEMPLATE.md`
- [ ] `BUSINESS_RULES.md` modul terdefinisi (mis. BR-WALLET-NNN)
- [ ] `DATABASE.md` modul + migrasi SQL siap & terdokumentasi
- [ ] `FLOW.md` (proses) & `API.md`/`UI.md` tersedia bila relevan
- [ ] Dependency phase sebelumnya selesai (lihat `IMPLEMENTATION_SEQUENCE.md`)
- [ ] Tidak ada keputusan arsitektur yang masih terbuka

### 19.4 Definition of Done (DoD)

> Acuan kanonik: **Project Constitution §14**. Butir di bawah diturunkan dari *checklist pre-commit & pre-release* yang **ada di repository** (`Repo`); penyempurnaan lanjutan bertanda `Planned Standard`.

Sebuah pekerjaan **Done** bila:

- [ ] Mengikuti `CODING_STANDARD` & `FOLDER_STRUCTURE` `Repo`
- [ ] Input tervalidasi & tersanitasi (server-side) `Repo`
- [ ] Memakai PDO prepared statements `Repo`
- [ ] Logging & audit trail untuk aksi penting `Repo`
- [ ] Tanpa rahasia/kredensial di kode `Repo`
- [ ] Tanpa hardcoded value untuk parameter konfigurabel `Repo`
- [ ] Sesuai Business Rules modul `Repo`
- [ ] Dokumentasi terkait diperbarui (terutama bila DB berubah) `Repo`
- [ ] Testing modul lolos (`TESTING_CHECKLIST.md`) `Repo`
- [ ] Modul finansial: rekonsiliasi seimbang & idempotency teruji `Repo`
- [ ] Responsif di mobile `Repo`
- [ ] Migrasi DB teruji & terdokumentasi `Repo`
- [ ] *(Planned)* Pemeriksaan keamanan OWASP terdokumentasi sebagai artefak review `Planned Standard`

### 19.5 Checklist Implementasi — Development

- [ ] DoR terpenuhi sebelum mulai (§19.3)
- [ ] Mengikuti alur §19.1 sesuai peran
- [ ] Tidak melanggar gerbang dokumentasi (§19.2)
- [ ] DoD terpenuhi sebelum dianggap selesai (§19.4)

> **Cross-reference:** Status modul → §14.4 & Lampiran B. Urutan phase → `IMPLEMENTATION_SEQUENCE.md`. AI roles → Bagian IV.

---

## 20. DOCUMENTATION WORKFLOW

Dokumentasi adalah artefak setara kode. **Dokumentasi usang dianggap cacat (*defect*).**

### 20.1 Prinsip `Repo`

- Dokumentasi diperbarui **sebagai bagian dari DoD** (bukan setelah rilis).
- Perubahan Business Rules → perbarui `BUSINESS_RULES.md` modul **lebih dulu**.
- Perubahan DB → perbarui `DATABASE.md` modul **sebelum** migrasi dianggap final.
- Penamaan & penomoran dokumen mengikuti Documentation Standards (Constitution §9): `DAYA-[NN]-[KODE]-[nama].md`.

### 20.2 Alur Pembaruan Dokumen

```
Perubahan diusulkan
   │
   ▼
Identifikasi dokumen terdampak
   (Business Rules? DATABASE.md? FLOW.md? PROJECT_BIBLE?)
   │
   ▼
Perbarui dokumen DALAM PR yang sama (atau PR tertaut)
   │
   ▼
Naikkan versi dokumen + Change Log (bila dokumen ber-versi)
   │
   ▼
Review → Merge  (dokumen & kode masuk bersama)
```

### 20.3 Hirarki Dokumen `Repo`

```
PROJECT_BIBLE.md            ← handbook & governance (dokumen ini)
   └─ PROJECT_CONSTITUTION  ← hukum tertinggi
      └─ MASTER_BLUEPRINT   ← peta seluruh dokumentasi (Frozen v1.0)
         └─ DOMAIN_MODEL    ← ubiquitous language
            └─ Standar       (CODING/DB/API/SECURITY/UI/FOLDER)
               └─ Dokumen modul (README, BUSINESS_RULES, DATABASE, …)
```

### 20.4 Checklist Implementasi — Documentation

- [ ] Dokumen terdampak teridentifikasi sebelum merge
- [ ] `BUSINESS_RULES.md`/`DATABASE.md` modul diperbarui bila relevan
- [ ] Versi dokumen + Change Log dinaikkan bila perlu
- [ ] Penamaan mengikuti Documentation Standards
- [ ] Tidak ada dokumen yang tertinggal usang

> **Cross-reference:** Document Governance → §G. Naming dokumen → Constitution §9. Lampiran C (indeks dokumen) — *menyusul*.

---

## 21. TESTING WORKFLOW

> Pengujian saat ini berbasis **checklist manual per modul** (`TESTING_CHECKLIST.md`) `Repo`. **Pengujian otomatis / CI bersifat `Planned Standard`** — belum ada di repository dan tidak boleh dianggap berjalan (selaras constraint tanpa NodeJS/Terminal wajib).

### 21.1 Lapisan Pengujian

| Lapisan | Status | Catatan |
|---|:---:|---|
| Checklist fungsional per modul | `Repo` | Setiap modul punya `TESTING_CHECKLIST.md`. |
| Pengujian keamanan dasar (OWASP) | `Repo` (pra-rilis) | Bagian dari checklist pra-rilis. |
| Pengujian rekonsiliasi finansial | `Repo` | Modul finansial: saldo seimbang, idempotency. |
| Unit/Integration test otomatis | `Planned Standard` | Framework & runner belum ditetapkan. |
| Continuous Integration (CI) | `Planned Standard` | Perlu jalur yang kompatibel shared hosting. |

### 21.2 Alur Pengujian

```
Fitur selesai (TRAE)
   │
   ▼
Jalankan TESTING_CHECKLIST.md modul
   │
   ├─ Modul finansial? ─► uji rekonsiliasi + idempotency (wajib seimbang)
   │
   ▼
Uji keamanan dasar (OWASP) + uji responsif mobile
   │
   ▼
{Semua lolos?} ── Tidak ──► kembali ke Development (§19)
   │ Ya
   ▼
Tandai DoD testing terpenuhi → lanjut Release/Deployment
```

### 21.3 Aturan Pengujian Modul Finansial `Repo`

- [ ] Ledger tetap **seimbang** (double-entry) setelah operasi
- [ ] Operasi **idempotent** (mis. webhook payment tidak menggandakan posting)
- [ ] Tidak ada mutasi saldo langsung (saldo selalu diturunkan dari ledger)

### 21.4 Checklist Implementasi — Testing

- [ ] `TESTING_CHECKLIST.md` modul dijalankan & lolos
- [ ] Uji keamanan dasar (OWASP) lolos
- [ ] Modul finansial: rekonsiliasi & idempotency teruji
- [ ] Responsif mobile diverifikasi
- [ ] Hasil uji didokumentasikan

> **Cross-reference:** DoD → §19.4. Security Standard → §15. Database/ledger → §12.

---

## 22. DEPLOYMENT WORKFLOW

Deployment ke **shared hosting** via cPanel/FastPanel — tanpa Docker/SSH/Terminal sebagai prasyarat.

### 22.1 Langkah Deployment `Repo`

```
1. Tag rilis (SemVer) di GitHub                     → v1.x.0
2. Backup PRA-RILIS (DB + file)                     → wajib
3. Upload kode via cPanel/FastPanel File Manager     (atau Git deploy bila tersedia)
4. Terapkan migrasi SQL via phpMyAdmin (file bernomor, berurutan)
5. Set konfigurasi environment DI LUAR web root      → storage/config/.env
6. Atur cron (rekonsiliasi, ekspirasi token, notifikasi)
7. Smoke test pasca-deploy                            → verifikasi rute kunci
```

### 22.2 Urutan Migrasi `Repo`

Migrasi dijalankan **berurutan sesuai dependency**. Contoh nyata (Collection): Authentication → Creator → Content → Collection → seeder permission. Selalu cek `DEPLOYMENT_NOTES.md` modul.

### 22.3 Verifikasi Pasca-Deploy `Repo`

- [ ] Guard instalasi (`app.installed`) sesuai (tidak redirect `/install` bila sudah terpasang)
- [ ] Rute publik & dashboard kunci dapat diakses
- [ ] Permission/RBAC berfungsi (mis. route admin hanya untuk role yang sesuai)
- [ ] Migrasi terbaru ter-apply tanpa error

### 22.4 Checklist Implementasi — Deployment

- [ ] Tag rilis SemVer dibuat
- [ ] Backup pra-rilis tersedia
- [ ] Migrasi SQL diterapkan berurutan via phpMyAdmin
- [ ] Konfigurasi/rahasia di luar web root
- [ ] Cron terpasang sesuai kebutuhan modul
- [ ] Smoke test lolos

> **Cross-reference:** Rantai menuju Production → §G.7. Database/migrasi → §12.3. Backup/DR sebagai NFR → §36.

---

## 23. RELEASE MANAGEMENT

Mengatur versioning, gerbang rilis, dan Change Management rilis.

### 23.1 Gerbang Rilis (Release Gate) `Repo`

Rilis hanya boleh berjalan bila checklist pra-rilis terpenuhi:

- [ ] Seluruh kriteria DoD terpenuhi (Constitution §14 / §19.4)
- [ ] Testing modul lolos (§21)
- [ ] Pemeriksaan keamanan dasar (OWASP) lolos
- [ ] Modul finansial: rekonsiliasi seimbang, idempotency teruji
- [ ] Responsif di mobile
- [ ] Migrasi DB teruji & terdokumentasi

### 23.2 Versioning & Tagging `Repo`

**Semantic Versioning** `MAJOR.MINOR.PATCH` (Constitution §12):

| Segmen | Naik saat | Catatan |
|---|---|---|
| MAJOR | Breaking change (mis. perubahan struktur ledger / kontrak API publik) | — |
| MINOR | Fitur kompatibel mundur | Perubahan perhitungan finansial **minimal** MINOR |
| PATCH | Perbaikan bug tanpa fitur baru | — |

- Pra-rilis: sufiks `1.0.0-beta.1`.
- Setiap rilis diberi **Git tag** = nomor versi & wajib punya **Change Log**.

### 23.3 Change Management Rilis `Repo`

```
Change Request ─► Impact Analysis ─► (finansial/keamanan? ─► Review Ketat)
       │                                          │
       └──────────────► Review Standar ───────────┘
                                │
                                ▼
                           Approval
                                ▼
              Update Dokumen + Versioning (§20, §23.2)
                                ▼
              Implementasi ─► Testing + DoD ─► Deploy + Changelog
```

Aturan: tidak ada perubahan pada dokumen `Frozen` tanpa Change Request tercatat; perubahan aturan bisnis memperbarui Business Rules lebih dulu; perubahan perhitungan dana melewati review Audit & Ledger.

### 23.4 Release Workflow Ringkas

```
develop stabil
   │
   ▼
PR develop → main  (Release Gate §23.1)
   │
   ▼
Merge main → Tag SemVer (§23.2) → Change Log
   │
   ▼
Deployment (§22) → Smoke test → Production
   │
   ▼
Monitoring & umpan balik → (siklus berikutnya)
```

### 23.5 Checklist Implementasi — Release

- [ ] Release Gate (§23.1) terpenuhi
- [ ] Versi dinaikkan sesuai SemVer & Change Log ditulis
- [ ] Git tag dibuat pada `main`
- [ ] Change Management diikuti (Review sesuai jalur)
- [ ] Deployment (§22) dijalankan dengan backup pra-rilis

> **Cross-reference:** Versioning dokumen → §G.5. Deployment → §22. Change Log proyek → §40.

---
---

> **— Akhir Bagian III —**

---
---

# BAGIAN IV — AI

> Bagian ini adalah **hukum kerja bagi seluruh AI** yang berkontribusi pada DAYA Platform Enterprise. AI manapun — ChatGPT, Claude, Nexla, TRAE, dan AI lain di masa depan — terikat bab ini. Aturan yang sudah ada di repository ditandai `Repo`; standar yang belum dikodifikasi sebagai dokumen/folder tersendiri ditandai **`Planned Standard`**.
>
> **Catatan roster.** Repository (`PROJECT_CONSTITUTION §10`) mendefinisikan lima pelaku: **ChatGPT, Claude, Nexla, TRAE, GitHub**. **Gemini** belum tercantum di repository sehingga seluruh perannya bertanda `Planned Standard` hingga disahkan. Tidak ditemukan folder `ai/` terpisah; materi AI bersumber dari Constitution §10 dan TRAE Project Kit.

---

## 24. AI CONSTITUTION

Konstitusi yang mengikat setiap AI sebagai **anggota tim**, bukan sekadar alat.

### 24.1 Role AI dalam Proyek `Repo`

| AI / Tool | Peran Resmi | Tanggung Jawab Utama |
|---|---|---|
| **ChatGPT** | CTO · Product/Business Architect · Reviewer | Strategi produk & bisnis, arsitektur tingkat tinggi, review akhir. |
| **Claude** | Documentation Architect · Technical Writer · Software Analyst | Menyusun & menjaga konsistensi dokumentasi, analisis kebutuhan, spesifikasi teknis. |
| **Nexla AI** | Rapid Application / CRUD / Dashboard Generator | Scaffolding aplikasi, modul CRUD, dashboard dari spesifikasi. |
| **TRAE AI** | Senior PHP Developer · Refactoring · Performance | Implementasi fitur, refactoring, optimasi, penyempurnaan kode. |
| **GitHub** | Source of Truth · Version Control | Pusat kebenaran kode & dokumen, kendali versi. |
| **Gemini** | *(peran belum ditetapkan)* | `Planned Standard` — definisikan peran sebelum dilibatkan. |

### 24.2 Yang Boleh Dilakukan AI `Repo`

- ✅ Membaca repository, `docs/`, dan dokumen acuan untuk memahami konteks.
- ✅ Mengerjakan tugas sesuai perannya (mis. Claude menyusun dokumen, TRAE mengimplementasi modul ✅ READY).
- ✅ Mengusulkan perubahan melalui Change Request.
- ✅ Berhenti dan bertanya bila ada yang tidak jelas.

### 24.3 Yang Dilarang bagi AI `Repo`

| ⛔ Dilarang | Sumber |
|---|---|
| Membuat **asumsi** di luar dokumentasi/repository | README_FIRST, TRAE Kit |
| Menulis kode untuk modul ber-status 🟡/🔴 sebelum dokumen lengkap & disetujui | README_FIRST, §14.4 |
| Mengubah **arsitektur** tanpa alasan kuat & persetujuan | Instruksi proyek |
| Membuat **modul baru tanpa instruksi** | Instruksi proyek |
| Mengubah **Business Rules** tanpa persetujuan tertulis | README_FIRST |
| Mengubah **Database** tanpa memperbarui `DATABASE.md` lebih dulu | README_FIRST |
| Menjadikan Docker/NodeJS/SSH/Terminal sebagai prasyarat | Constitution §7 |
| Menyimpan rahasia ke repository | Security Standard §15 |
| Menyimpang dari **Master Blueprint** & **Constitution** | Constitution §10 |

### 24.4 AI Review Process `Repo` / `Planned Standard`

Mengikuti jalur review proyek: perubahan finansial/keamanan → **Review Ketat (Architect + Security)**; selain itu **Review Standar** (`Repo`, §18.4). Checklist review khusus-AI yang formal bersifat `Planned Standard`.

```
Output AI (dokumen / kode / scaffolding)
        │
        ▼
  Self-check terhadap PROJECT_BIBLE + Standar
        │
        ▼
  Review oleh AI lain / manusia (sesuai jalur §18.4)
        │
        ▼
  Human Approval (§24.5)  ──► Merge ke GitHub (SoT)
```

### 24.5 Human Approval `Repo`

- Persetujuan akhir merge ke `main` berada pada **Owner / Chief Software Architect** (§G.2).
- AI **tidak** memiliki wewenang menyetujui perubahannya sendiri ke Source of Truth.
- Approval ChatGPT (sebagai Reviewer) bersifat rekomendasi arsitektural, **bukan** pengganti Human Approval untuk merge.

### 24.6 Source of Truth bagi AI `Repo`

GitHub (branch `main`) adalah satu-satunya sumber kebenaran. Bila dokumen dan kode berbeda, **kode yang berlaku**. Tidak ada AI yang boleh memperlakukan asumsi internalnya sebagai kebenaran proyek.

### 24.7 AI Memory Policy (Ringkas) `Planned Standard`

Detail penuh di §28. Inti: AI **tidak menyimpan** rahasia/kredensial; AI memperlakukan **frozen rules** (Constitution, Master Blueprint) sebagai konteks tetap; konteks yang berlaku selalu diverifikasi ulang dari repository, bukan dari ingatan lama.

### 24.8 AI Context Strategy `Planned Standard`

- Selalu muat ulang konteks dari **PROJECT_BIBLE → Constitution → Master Blueprint → Domain Model → Standar → dokumen modul**.
- Untuk modul yang sudah berkode, **kode adalah konteks final** (lihat Lampiran B).
- Jangan bekerja dari ringkasan usang bila sumber aslinya tersedia di repo.

### 24.9 AI Collaboration Rules `Repo`

- Tidak ada AI yang menyimpang dari Master Blueprint & Constitution.
- Hand-off antar-AI mengikuti rantai peran (§25).
- Setiap keluaran AI dapat ditelusuri ke dokumen sumbernya (traceability).

### 24.10 Checklist Implementasi — AI Constitution

- [ ] AI telah membaca PROJECT_BIBLE + Constitution sebelum bekerja
- [ ] Tugas sesuai peran AI (§24.1)
- [ ] Tidak melanggar daftar larangan (§24.3)
- [ ] Output melewati review sesuai jalur (§24.4)
- [ ] Human Approval diperoleh sebelum merge (§24.5)

> **Cross-reference:** Code review → §18.4. Document Governance → §G. Status modul → §14.4 & Lampiran B.

---

## 25. AI WORKFLOW

Workflow nyata kolaborasi AI pada DAYA Platform Enterprise.

### 25.1 Diagram Alur Kolaborasi `Repo`

```
        ┌──────────────────────────────┐
        │ ChatGPT (CTO/Architect/Review)│
        └───────────────┬──────────────┘
              arahan & review
                        ▼
        ┌──────────────────────────────┐
        │ Claude (Docs/Analyst/Writer)  │
        └───────┬───────────────┬──────┘
       spesifikasi&blueprint  spesifikasi teknis
               ▼               │
   ┌────────────────────┐      │
   │ Nexla (Scaffolding)│      │
   └─────────┬──────────┘      │
        kode awal              │
               └───────┬───────┘
                       ▼
        ┌──────────────────────────────┐
        │ TRAE (PHP Dev/Refactor/Perf)  │
        └───────────────┬──────────────┘
                  kode final
                        ▼
        ┌──────────────────────────────┐
        │  GitHub  (SOURCE OF TRUTH)    │◄── ChatGPT approval
        └───────────────┬──────────────┘
                        │ acuan tunggal
              ┌─────────┼─────────┐
              ▼         ▼         ▼
          ChatGPT     Claude     TRAE   (semua membaca dari GitHub)
```

### 25.2 Peran per AI dalam Workflow

| AI | Input | Output | Status |
|---|---|---|:---:|
| **ChatGPT** | Kebutuhan & konteks proyek | Arahan strategi, keputusan arsitektur, approval review | `Repo` |
| **Claude** | Arahan ChatGPT + repository | Dokumentasi, spesifikasi teknis, analisis (mis. PROJECT_BIBLE ini) | `Repo` |
| **Nexla** | Spesifikasi & blueprint Claude | Scaffolding, CRUD, dashboard | `Repo` |
| **TRAE** | Spesifikasi teknis + kode awal Nexla | Kode fitur final sesuai standar | `Repo` |
| **GitHub** | Kode & dokumen final | Source of Truth + versioning | `Repo` |
| **Gemini** | *(belum ditetapkan)* | *(belum ditetapkan)* | `Planned Standard` |

> **Penempatan Gemini (`Planned Standard`).** Bila kelak dilibatkan, Gemini perlu peran eksplisit (mis. riset, analisis multimodal, atau review pelengkap) yang tidak tumpang tindih dengan peran yang sudah ada, dan harus melewati pengesahan via Change Request sebelum masuk workflow ini.

### 25.3 Aturan Hand-off Antar-AI `Repo`

- Hand-off membawa serta **rujukan dokumen sumber**, bukan sekadar hasil.
- Penerima hand-off **memverifikasi** terhadap repository sebelum melanjutkan.
- Tidak ada tahap yang melompati gerbang dokumentasi (§19.2).

### 25.4 Checklist Implementasi — AI Workflow

- [ ] Tugas masuk melalui peran yang benar (§25.2)
- [ ] Hand-off menyertakan rujukan dokumen sumber
- [ ] Output final menuju GitHub melalui review + approval
- [ ] Gemini (bila dipakai) sudah disahkan perannya

> **Cross-reference:** Development Workflow → §19.1. Git/PR → §18. AI Constitution → §24.

---

## 26. AI PROMPT STANDARD

> **`Planned Standard`** — belum ada dokumen standar prompt tersendiri di repository. Namun strukturnya **diturunkan** dari format prompt nyata pada `TRAE_PROJECT_KIT/PROMPTS/*` (`Repo`) yang sudah memuat Tujuan, Dokumen Referensi, Output, DoD, Larangan, dan Checklist.

### 26.1 Lima Komponen Wajib

| Komponen | Isi |
|---|---|
| **Objective** | Apa yang harus dicapai — satu tujuan jelas & terukur. |
| **Context** | Modul/bagian terkait, dokumen acuan, status saat ini, dependency. |
| **Constraints** | Batasan wajib: stack, larangan (Docker/Node/SSH), standar (Coding/DB/Security), gerbang dokumentasi. |
| **Expected Output** | Bentuk keluaran konkret (dokumen `.md`, kode modul, migrasi SQL, dsb.) + lokasi file. |
| **Acceptance Criteria** | Kriteria lolos (DoD relevan, checklist, uji) sebelum dianggap selesai. |

### 26.2 Template Prompt Enterprise `Planned Standard`

```
# [JUDUL TUGAS] — DAYA Platform Enterprise

## OBJECTIVE
<satu tujuan jelas & terukur>

## CONTEXT
- Modul/Bagian   : <nama modul / bab>
- Status saat ini: <✅/🟡/🔴 + ringkas>
- Dokumen acuan  : PROJECT_BIBLE.md · PROJECT_CONSTITUTION.md · <modul>/BUSINESS_RULES.md · ...
- Dependency     : <phase/modul prasyarat>
- Source of Truth: GitHub branch main (kode = acuan utama)

## CONSTRAINTS
- Stack          : PHP Native 8.x + MySQL (InnoDB) + Bootstrap 5 + Vanilla JS
- Dilarang       : Docker / NodeJS / SSH / Terminal sebagai prasyarat
- Wajib patuh    : CODING_STANDARD · FOLDER_STRUCTURE · DATABASE_STANDARD · SECURITY_STANDARD · UI_STANDARD
- Gerbang dok.   : modul 🟡/🔴 tidak boleh dikodekan sebelum dokumen lengkap
- Tanpa asumsi   : bila tidak jelas → BERHENTI & tanya

## EXPECTED OUTPUT
- Bentuk         : <dokumen .md / kode modul / migrasi SQL / scaffolding>
- Lokasi file    : <path tepat, mis. docs/... atau app/modules/...>
- Format         : <Markdown enterprise / kode sesuai standar>

## ACCEPTANCE CRITERIA
- [ ] Memenuhi Definition of Done relevan (§19.4)
- [ ] Lolos checklist modul (TESTING_CHECKLIST bila kode)
- [ ] Dokumen terkait diperbarui
- [ ] Tidak ada rahasia / hardcoded value konfigurabel
- [ ] (finansial) double-entry + idempotent + audit trail
```

### 26.3 Prinsip Penulisan Prompt

- Satu prompt = satu tujuan; hindari menggabung banyak tugas tak-terkait.
- Selalu cantumkan **Source of Truth** dan **larangan asumsi**.
- Sertakan **path file** yang tepat agar output dapat ditelusuri.

### 26.4 Checklist Implementasi — Prompt Standard

- [ ] Lima komponen wajib lengkap (§26.1)
- [ ] Dokumen acuan & dependency dicantumkan
- [ ] Constraints & larangan eksplisit
- [ ] Acceptance Criteria dapat diuji

> **Cross-reference:** Prompt nyata → `TRAE_PROJECT_KIT/PROMPTS/`. DoD → §19.4. Constraints stack → §9.

---

## 27. AI HANDOVER STANDARD

> **`Planned Standard`** sebagai dokumen tersendiri, namun **berakar** pada "Urutan Membaca Wajib" di `README_FIRST.md` (`Repo`). Mengatur bagaimana AI baru (atau sesi baru) mengambil alih proyek tanpa kehilangan konteks.

### 27.1 Alur Handover

```
AI baru / sesi baru masuk
   │
   ▼
1. Baca PROJECT_BIBLE.md           ← pedoman & governance (dokumen ini)
   ▼
2. Baca docs/ + Constitution + Master Blueprint + Domain Model
   ▼
3. Pelajari repository (app/, database/) ← Source of Truth
   ▼
4. Baca Change Log (§40)            ← perubahan terbaru
   ▼
5. Pahami Source of Truth           ← kode > dokumen
   ▼
6. Pahami Roadmap (§37)             ← arah & prioritas
   ▼
7. Pahami Module Status (Lampiran B)← apa yang sudah/belum berkode
   ▼
Siap berkontribusi (sesuai peran §24.1)
```

### 27.2 Checklist Handover Wajib

- [ ] Sudah membaca **PROJECT_BIBLE.md** secara penuh
- [ ] Sudah membaca **docs/** (standar + keputusan arsitektur)
- [ ] Sudah mempelajari **repository** (`app/`, `database/`) sebagai Source of Truth
- [ ] Sudah membaca **Change Log** (§40) untuk perubahan terbaru
- [ ] Memahami **Source of Truth** (kode > dokumen bila konflik)
- [ ] Memahami **Roadmap** (§37) & prioritas
- [ ] Memahami **Module Status** (Lampiran B: kode vs dokumen)
- [ ] Memahami peran AI yang diambil (§24.1) & batasannya (§24.3)

### 27.3 Aturan Handover

- AI baru **tidak** boleh langsung menghasilkan kode/dokumen sebelum checklist §27.2 tuntas.
- Bila menemukan inkonsistensi dokumen vs kode → laporkan & ikuti **kode**, jangan diam-diam memilih dokumen.

### 27.4 Checklist Implementasi — Handover

- [ ] Checklist §27.2 lengkap sebelum kontribusi pertama
- [ ] Inkonsistensi (bila ada) dicatat untuk rekonsiliasi
- [ ] Peran & batasan dipahami

> **Cross-reference:** Urutan baca → `README_FIRST.md`. Module status → Lampiran B. Roadmap → §37.

---

## 28. AI MEMORY STANDARD

> **`Planned Standard`** — kebijakan memori bagi AI contributor pada level proyek (bukan implementasi teknis memori suatu AI tertentu). Tujuannya menjaga konsistensi konteks & keamanan lintas sesi/AI.

### 28.1 Apa yang Boleh Disimpan

| Boleh Disimpan | Alasan |
|---|---|
| Konteks proyek dari PROJECT_BIBLE/Constitution/Blueprint | Konteks tetap yang menjaga konsistensi |
| Ubiquitous language (Domain Model) | Mencegah sinonim & ambiguitas |
| Status modul & roadmap (sebagai rujukan, diverifikasi ulang) | Orientasi kerja |
| Keputusan arsitektur (ADR, §35) | Mencegah pengulangan keputusan |

### 28.2 Apa yang TIDAK Boleh Disimpan `Repo` (prinsip keamanan)

| Dilarang Disimpan | Alasan |
|---|---|
| Rahasia/kredensial (API key, password, `.env`) | Pelanggaran keamanan; rahasia di luar repo (§15) |
| Data pengguna sensitif / PII di luar kebutuhan tugas | Privasi & compliance (§33) |
| Asumsi yang tidak terverifikasi sebagai "fakta proyek" | Melanggar larangan asumsi (§24.3) |
| Ringkasan usang yang menggantikan sumber asli | Source of Truth harus diverifikasi ulang |

### 28.3 Memory Hierarchy

```
┌─────────────────────────────────────────────┐
│ FROZEN RULES (tetap)                         │
│  PROJECT_CONSTITUTION · MASTER_BLUEPRINT     │
│  → tidak berubah tanpa Change Management     │
├─────────────────────────────────────────────┤
│ PROJECT CONTEXT (stabil, lintas sesi)        │
│  PROJECT_BIBLE · Domain Model · Standar      │
│  Module Status · Roadmap                     │
├─────────────────────────────────────────────┤
│ SESSION CONTEXT (sementara, per tugas)       │
│  tugas aktif · file yang sedang dikerjakan   │
│  → tidak dipromosikan jadi fakta tanpa verif.│
└─────────────────────────────────────────────┘
        Verifikasi selalu mengalir ke ATAS:
   Session diverifikasi thd Project; Project tunduk Frozen.
```

### 28.4 Project Context vs Session Context

| Aspek | Project Context | Session Context |
|---|---|---|
| Umur | Stabil, lintas sesi | Sementara, satu tugas |
| Sumber | PROJECT_BIBLE, Standar, Domain Model | Instruksi tugas saat ini |
| Otoritas | Tinggi (setelah kode) | Rendah — wajib diverifikasi |
| Contoh | "Ledger append-only & immutable" | "Sedang menulis Bagian IV Bible" |

### 28.5 Frozen Rules `Repo`

Aturan berstatus `🔒 Frozen` (Constitution, Master Blueprint v1.0) diperlakukan sebagai **konteks tetap**. AI tidak boleh "melupakan" atau menimpanya berdasarkan instruksi sesi; perubahan hanya melalui Change Management (§G.6, §23.3).

### 28.6 Checklist Implementasi — Memory

- [ ] Tidak menyimpan rahasia/kredensial/PII di luar kebutuhan
- [ ] Frozen rules diperlakukan sebagai konteks tetap
- [ ] Session context diverifikasi terhadap project context sebelum dijadikan acuan
- [ ] Sumber asli (repo) diutamakan di atas ringkasan

> **Cross-reference:** Security → §15. Compliance/PII → §33. Frozen & Change Mgmt → §G.6, §23.3. Context strategy → §24.8.

---
---

> **— Akhir Bagian IV —**

---
---

# BAGIAN V — ENTERPRISE GOVERNANCE

> Enterprise Governance Handbook DAYA Platform: aturan bisnis, filosofi bagi hasil, tata kelola peran, manajemen risiko, dan visi masa depan. Status tiap aturan dipisahkan tegas: **Implemented** (`Repo`, sudah berkode), **Planned** (dokumen/rencana, belum berkode), **Future** (arah jangka panjang).

---

## 29. BUSINESS RULES

> Pemisahan status: **🟢 Implemented** (terverifikasi di kode/migrasi) · **🟡 Planned** (dokumen ada / dirancang, belum berkode) · **🔵 Future** (belum dirancang). Penomoran BR bersifat referensial untuk PROJECT_BIBLE; katalog kanonik per modul tetap di `<modul>/BUSINESS_RULES.md`.

### 29.1 Core Business Rules

| Kode | Aturan | Status |
|---|---|:---:|
| BR-CORE-01 | `User` adalah identitas dasar; `Creator`/`Audience`/`Affiliate` adalah role specialization, satu user bisa multi-role. | 🟢 Implemented |
| BR-CORE-02 | Status user terkontrol enum: `pending_verification`, `active`, `suspended`, `deactivated`, `banned`. | 🟢 Implemented |
| BR-CORE-03 | Password disimpan sebagai hash (`password_hash`); token sensitif disimpan sebagai hash. | 🟢 Implemented |
| BR-CORE-04 | Akses dikontrol RBAC (role + permission, least privilege). | 🟢 Implemented |
| BR-CORE-05 | Setiap aksi penting memiliki audit trail (mis. `login_history`). | 🟢 Implemented |
| BR-CORE-06 | Uang disimpan sebagai integer minor unit (BIGINT) di seluruh sistem. | 🟡 Planned |
| BR-CORE-07 | Kebenaran finansial hanya di Ledger (append-only, immutable, double-entry). | 🟡 Planned |
| BR-CORE-08 | Parameter ekonomi (fee/komisi/% misi) dikonfigurasi, bukan hardcoded. | 🔵 Future |

### 29.2 Creator Economy Rules

| Kode | Aturan | Status |
|---|---|:---:|
| BR-CRT-01 | Hanya creator sah & aktif yang dapat mempublikasikan karya & menerima pendapatan. | 🟢 Implemented |
| BR-CRT-02 | Onboarding creator: `draft → pending_review → active/rejected/suspended/revoked`, dengan review admin. | 🟢 Implemented |
| BR-CRT-03 | Aplikasi creator menyertakan data KYC (nama, jenis & nomor dokumen). | 🟢 Implemented |
| BR-CRT-04 | Karya dimiliki creator (`creator_id`), bukan user biasa. | 🟢 Implemented |
| BR-CRT-05 | `CreatorTier` immutable & ditentukan kriteria; `MissionScore` mengukur dampak. | 🟡 Planned |
| BR-CRT-06 | `CreatorHandle` (slug) unik untuk profil & URL publik. | 🟢 Implemented |

### 29.3 Content & Marketplace Rules

| Kode | Aturan | Status |
|---|---|:---:|
| BR-CNT-01 | `Content` adalah aggregate root tunggal seluruh karya. | 🟢 Implemented |
| BR-CNT-02 | Jenis karya = `content_type`, bukan modul/tabel terpisah per jenis. | 🟢 Implemented |
| BR-CNT-03 | Lifecycle: `draft → in_review → published → rejected/archived/removed`; publik hanya melihat `published`. | 🟢 Implemented |
| BR-CNT-04 | `slug` Content unik untuk URL SEO-friendly. | 🟢 Implemented |
| BR-CNT-05 | `Content Part` adalah sub-entitas, diakses hanya via `Content`. | 🟢 Implemented |
| BR-CNT-06 | `Collection` mengelompokkan banyak Content: `Creator → Collection → CollectionItem → Content`; tidak menyimpan isi karya. | 🟢 Implemented |
| BR-CNT-07 | Directory publik Collection hanya menampilkan `published` + `visibility = public`. | 🟢 Implemented |
| BR-MKT-01 | Marketplace lintas-creator (etalase, kurasi, transaksi terpusat). | 🔵 Future |

### 29.4 Membership & Subscription Rules

| Kode | Aturan | Status |
|---|---|:---:|
| BR-ACC-01 | `AccessPolicy` Content: gratis / berbayar / membership-only. | 🟡 Planned *(model akses di domain; enforcement berbayar menunggu Wallet/Payment)* |
| BR-MEM-01 | Membership memberi akses ke konten membership-only milik creator. | 🟡 Planned |
| BR-SUB-01 | Subscription berulang (recurring) ke creator/konten. | 🟡 Planned |
| BR-SUB-02 | Penagihan & siklus langganan tunduk pada Payment + Audit & Ledger. | 🟡 Planned |

### 29.5 Affiliate & Commission Rules

| Kode | Aturan | Status |
|---|---|:---:|
| BR-AFF-01 | Affiliate adalah role specialization; menerima komisi atas rujukan/penjualan. | 🟡 Planned |
| BR-AFF-02 | Affiliate Engine memiliki anti-fraud & abuse prevention + audit trail. | 🟡 Planned |
| BR-COM-01 | Komisi dihitung dari parameter konfigurabel (bukan hardcoded). | 🟡 Planned |
| BR-COM-02 | Setiap komisi tercatat di ledger (tidak ada komisi tanpa jejak). | 🟡 Planned |

### 29.6 Wallet Rules `Planned`

| Kode | Aturan | Status |
|---|---|:---:|
| BR-WALLET-* | Katalog 30+ aturan (BR-WALLET-NNN) pada pilot Wallet. | 🟡 Planned |
| BR-WAL-01 | Saldo **diturunkan** dari ledger, tidak pernah dimutasi langsung. | 🟡 Planned |
| BR-WAL-02 | Setiap mutasi adalah posting double-entry yang seimbang. | 🟡 Planned |
| BR-WAL-03 | Ledger append-only & immutable (ditegakkan di level privilege DB bila hosting mengizinkan). | 🟡 Planned |
| BR-WAL-04 | Credit adalah anggota aggregate Wallet (akses via root Wallet). | 🟡 Planned |

### 29.7 Payment Rules `Planned`

| Kode | Aturan | Status |
|---|---|:---:|
| BR-PAY-01 | Integrasi gateway **Duitku** (target Phase 8). | 🟡 Planned |
| BR-PAY-02 | Webhook **idempotent** (tidak menggandakan posting/transaksi). | 🟡 Planned |
| BR-PAY-03 | Integrasi gateway bersifat abstrak (memungkinkan fallback manual). | 🟡 Planned |
| BR-PAY-04 | Seluruh pergerakan dana payment tercatat di ledger. | 🟡 Planned |

### 29.8 Checklist Implementasi — Business Rules

- [ ] Aturan baru diberi kode BR & status (Implemented/Planned/Future)
- [ ] Katalog kanonik di `<modul>/BUSINESS_RULES.md` diperbarui lebih dulu
- [ ] Aturan finansial mematuhi Audit & Ledger (double-entry, idempotent)
- [ ] Tidak ada aturan finansial yang mem-bypass ledger

> **Cross-reference:** Database/ledger → §12. Revenue → §30. Status modul → Appendix D. Domain → `DOMAIN_MODEL.md`.

---

## 30. REVENUE SHARING PHILOSOPHY

> Filosofi bagi hasil DAYA. **Angka persentase bersifat ilustratif (`Planned`)** — nilai final mengikuti dokumen **Revenue Sharing Engine (#31)** & **Revenue Model (#7)** yang belum disahkan. Jangan memperlakukan angka pada §30.3 sebagai kebijakan resmi.

### 30.1 Lima Prinsip

| Prinsip | Makna |
|---|---|
| **Creator First** | Creator memperoleh bagian terbesar & adil dari nilai karyanya. |
| **Platform Sustainability** | Platform mengambil bagian secukupnya untuk keberlanjutan operasional, bukan ekstraksi maksimal. |
| **Fair Revenue** | Pembagian transparan, dapat diverifikasi, dan tercatat di ledger. |
| **Ecosystem Growth** | Sebagian nilai mendukung pertumbuhan ekosistem (affiliate, misi sosial). |
| **Long Term Value** | Keputusan bagi hasil menjaga kepercayaan jangka panjang di atas keuntungan jangka pendek. |

### 30.2 Aliran Nilai (Konseptual)

```
        Pembayaran Audiens (100%)
                 │
                 ▼
        ┌─────────────────────┐
        │   Revenue Splitter   │ ← parameter konfigurabel (#31)
        └──────┬───────┬───────┘
               │       │        │            │
               ▼       ▼        ▼            ▼
           Creator  Platform  Affiliate   Mission/Foundation
           (utama)   (fee)    (komisi)    (alokasi misi)
               │       │        │            │
               └───────┴────────┴────────────┘
                         ▼
                  LEDGER (double-entry, immutable)
            setiap bagian tercatat sebagai posting
```

> **Catatan.** Foundation menerima nilai **hanya** via Revenue Sharing atau Sponsor (entity governance, bukan pelaku transaksi langsung).

### 30.3 Tabel Simulasi Revenue Sharing *(ilustratif — `Planned`)*

> ⚠️ **Angka di bawah adalah contoh ilustrasi mekanisme**, bukan kebijakan. Uang dihitung dalam minor unit; ditampilkan dalam Rupiah untuk keterbacaan.

Contoh transaksi penjualan konten senilai **Rp100.000** (skenario ilustratif):

| Penerima | Porsi (ilustratif) | Nilai (ilustratif) | Pencatatan |
|---|:---:|---:|---|
| Creator | 70% | Rp70.000 | Posting kredit ke Wallet creator |
| Platform (fee) | 15% | Rp15.000 | Posting pendapatan platform |
| Affiliate (komisi) | 10% | Rp10.000 | Posting komisi (bila ada rujukan) |
| Mission/Foundation | 5% | Rp5.000 | Posting alokasi misi |
| **Total** | **100%** | **Rp100.000** | Seimbang (double-entry) |

Skenario tanpa affiliate (ilustratif): porsi affiliate dapat dialihkan sesuai aturan #31 — **mekanisme final mengikuti Revenue Sharing Engine**.

### 30.4 Checklist Implementasi — Revenue Sharing

- [ ] Persentase diambil dari Configuration Engine (bukan hardcoded)
- [ ] Setiap bagian menjadi posting ledger yang seimbang
- [ ] Alokasi misi tercatat & dapat diaudit publik
- [ ] Total split selalu = 100% (tidak ada nilai "hilang")

> **Cross-reference:** Business Rules finansial → §29.5–29.7. Ledger → §12. Mission allocation → #32 (dokumen). Risiko ketidakpercayaan alokasi → §32 (R-FIN/R-COMP).

---

## 31. GOVERNANCE RULES

Tata kelola peran proyek DAYA Platform Enterprise.

### 31.1 Definisi Peran

| Peran | Tanggung Jawab | Sumber |
|---|---|:---:|
| **Owner** | Pemilik produk; keputusan akhir & Human Approval untuk merge ke `main`. | `Repo` |
| **Chief Software Architect** | Penjaga arsitektur & standar; menyetujui perubahan arsitektural; satu-satunya penaik MAJOR version dokumen. | `Repo` |
| **Lead Developer** | Memimpin implementasi; menjaga kualitas kode lintas modul. | `Planned` |
| **Contributor** | Developer/AI yang mengerjakan tugas via Change Request. | `Repo` |
| **AI Assistant** | ChatGPT/Claude/Nexla/TRAE (+Gemini `Planned`) sesuai peran §24.1. | `Repo` |
| **Reviewer** | Menelaah konsistensi & dampak (Standar/Ketat). | `Repo` |
| **Tester / QA** | Menjalankan `TESTING_CHECKLIST`, uji keamanan & rekonsiliasi finansial. | `Repo` |

### 31.2 Responsibility Matrix (RACI) `Planned Standard`

> R = Responsible · A = Accountable · C = Consulted · I = Informed. Matriks formal belum ada di repo; disusun di sini dari peran yang berlaku.

| Aktivitas | Owner | Architect | Lead Dev | Contributor | AI Assistant | Reviewer | Tester |
|---|:--:|:--:|:--:|:--:|:--:|:--:|:--:|
| Blueprint & Strategi | A | R | C | I | C | C | I |
| Business Rules | A | C | C | I | R | C | I |
| Database Design | I | A | C | I | R | C | I |
| Implementasi Kode | I | C | A | R | R | C | I |
| Code Review | I | C | C | I | C | A/R | I |
| Testing | I | I | C | C | I | C | A/R |
| Dokumentasi | C | A | C | R | R | C | I |
| Deployment | A | C | R | C | I | I | C |
| Approval Merge ke `main` | A/R | C | C | I | I | C | I |
| Change Management | A | R | C | C | C | C | I |

### 31.3 Aturan Tata Kelola `Repo`

- Tidak ada perubahan ke `main` tanpa Human Approval (§24.5, §G.2).
- AI tidak menyetujui perubahannya sendiri ke Source of Truth.
- Perubahan finansial/keamanan wajib Review Ketat.

### 31.4 Checklist Implementasi — Governance

- [ ] Setiap tugas punya Accountable yang jelas
- [ ] Review mengikuti jalur (Standar/Ketat)
- [ ] Human Approval diperoleh sebelum merge
- [ ] Perubahan tercatat (commit + PR + Change Log)

> **Cross-reference:** Document Governance → §G. AI Constitution → §24. Code review → §18.4.

---

## 32. RISK MANAGEMENT

> Risk Register lintas kategori. Beberapa baris berakar pada Project Constitution §17 (`Repo`); sisanya diperluas untuk menutup kategori yang diminta. Probability & Impact: Rendah / Sedang / Tinggi.

### 32.1 Risk Register

| Kode | Risiko | Kategori | Probability | Impact | Mitigasi | Owner |
|---|---|---|:--:|:--:|---|---|
| R-TEC-01 | Korupsi/kehilangan data | Technical | Rendah | Tinggi | Backup terjadwal, DR plan, integritas transaksional InnoDB. | Lead Dev |
| R-TEC-02 | Keterbatasan shared hosting (resource) | Technical | Sedang | Sedang | Desain hemat, index-aware queries, caching ringan, jalur evolusi ke VPS. | Architect |
| R-SEC-01 | Kerentanan OWASP (injection, XSS, dsb.) | Security | Sedang | Tinggi | Prepared statements, CSRF, validasi server-side, checklist OWASP. | Architect |
| R-SEC-02 | Kebocoran rahasia/kredensial | Security | Rendah | Tinggi | Rahasia di luar web root, larangan commit secret, review. | Owner |
| R-FIN-01 | Saldo tidak konsisten / double-spend | Financial | Rendah | Tinggi | Ledger double-entry immutable, idempotency, rekonsiliasi. | Architect |
| R-FIN-02 | Ketergantungan payment gateway pihak ketiga | Financial | Sedang | Sedang | Integrasi abstrak, webhook idempotent, fallback manual. | Lead Dev |
| R-FIN-03 | Penyalahgunaan affiliate (fraud) | Financial/Security | Sedang | Sedang | Anti-fraud Affiliate Engine, audit trail. | Lead Dev |
| R-COM-01 | Ketidakpercayaan publik atas alokasi misi | Compliance/Social | Rendah | Tinggi | Transparansi & pelaporan dampak publik, audit ledger misi. | Owner |
| R-COM-02 | Ketidaksesuaian regulasi (privasi/keuangan) | Compliance | Sedang | Tinggi | Minimalkan PII, audit trail, tinjauan kepatuhan berkala. | Owner |
| R-AI-01 | AI membuat asumsi di luar repository | AI | Sedang | Sedang | Larangan asumsi (§24.3), gerbang dokumentasi, Human Approval. | Architect |
| R-AI-02 | AI menyimpang dari arsitektur/standar | AI | Sedang | Tinggi | AI Constitution (§24), review, Source of Truth = kode. | Architect |
| R-HUM-01 | Pengetahuan terpusat pada sedikit orang (bus factor) | Human | Sedang | Sedang | Dokumentasi-first, PROJECT_BIBLE, handover standard (§27). | Owner |
| R-HUM-02 | Inkonsistensi antar kontributor | Human | Sedang | Sedang | Standar tunggal (Coding/Module), review, ubiquitous language. | Lead Dev |
| R-OPS-01 | Dokumentasi usang | Operational | Sedang | Sedang | "Update docs = bagian DoD", Change Log wajib. | Architect |
| R-OPS-02 | Deployment manual error (migrasi/urutan) | Operational | Sedang | Tinggi | `DEPLOYMENT_NOTES` per modul, backup pra-rilis, smoke test. | Lead Dev |

### 32.2 Prinsip Mitigasi

- Risiko finansial & keamanan **selalu** prioritas tertinggi (selaras urutan trade-off §1.2).
- Setiap risiko punya **Owner** yang akuntabel.
- Mitigasi yang sudah jadi praktik kode ditandai melalui Standar terkait (§12, §15).

### 32.3 Checklist Implementasi — Risk

- [ ] Risiko baru dimasukkan ke register dengan Probability/Impact/Mitigasi/Owner
- [ ] Risiko finansial/keamanan ditinjau pada setiap perubahan terkait
- [ ] Mitigasi terhubung ke standar/aktivitas konkret
- [ ] Owner risiko teridentifikasi

> **Cross-reference:** Security → §15. Ledger/finansial → §12, §29. AI risk → §24. Operational/deploy → §22.

---

## 33. FUTURE VISION

> Visi bertahap DAYA Platform Enterprise. **Phase 1** berakar pada kondisi nyata (`Repo`); fase berikutnya adalah arah (`Planned`/`Future`) yang selaras dengan Future Evolution Constitution §18 (V1→V5). Timeline bersifat relatif/ilustratif — tanpa tanggal pasti karena belum ada komitmen tanggal di repository.

### 33.1 Tujuh Fase

| Phase | Nama | Fokus | Status | Selaras dengan |
|:--:|---|---|:--:|---|
| 1 | **Foundation** | Framework modular, Authentication, Creator, Content, Collection. | 🟢 Berjalan | V1 Single Tenant |
| 2 | **Creator Platform** | Wallet, Payment (Duitku), monetisasi karya, tier & mission score. | 🟡 Planned | V1.x Hardening |
| 3 | **Marketplace** | Etalase lintas-creator, kurasi, discovery, transaksi terpusat. | 🔵 Future | V1.x–V2 |
| 4 | **Creator Ecosystem** | Affiliate, Sponsor, Foundation, revenue sharing & alokasi misi penuh. | 🔵 Future | V2 Multi-Brand |
| 5 | **Enterprise Platform** | Multi-tenant SaaS, isolasi data, SLA, audit & compliance matang. | 🔵 Future | V3–V5 |
| 6 | **Regional Expansion** | Multi-bahasa/mata uang, kepatuhan lintas wilayah. | 🔵 Future | pasca V3 |
| 7 | **AI-Native Platform** | Otomasi AI mendalam (rekomendasi, moderasi, asistensi creator). | 🔵 Future | visi jangka panjang |

### 33.2 Timeline Sederhana (Relatif)

```
 NOW ──────────────► NEXT ──────────────► LATER ──────────────► VISION
  │                    │                     │                     │
 [1] Foundation     [2] Creator         [3] Marketplace      [6] Regional
 🟢 Berjalan         Platform 🟡         [4] Ecosystem 🔵     [7] AI-Native 🔵
                                         [5] Enterprise 🔵
  ├─ Auth/Creator/   ├─ Wallet/Payment    ├─ Affiliate/Sponsor ├─ Multi-bahasa
  │  Content/Collec. │  monetisasi        │  Foundation/Mission │  Multi-tenant SaaS
  └─ Modular core    └─ tier & score      └─ revenue sharing    └─ AI otomasi
```

### 33.3 Prinsip Evolusi `Repo`

Setiap keputusan teknis di fase awal **wajib** mempertimbangkan dampaknya pada jalur evolusi. **Tidak boleh** ada keputusan yang menutup pintu menuju multi-tenant atau cloud (Constitution §18).

### 33.4 Checklist Implementasi — Future Vision

- [ ] Keputusan teknis tidak menutup jalur evolusi (multi-tenant/cloud)
- [ ] Fitur baru dipetakan ke fase yang sesuai
- [ ] Ledger & konfigurasi dijaga portabel untuk skala berikutnya

> **Cross-reference:** Future Evolution → Constitution §18. Roadmap modul → `IMPLEMENTATION_SEQUENCE.md`. Status modul → Appendix D.

---
---

# CLOSING — DOCUMENT STATUS & APPROVAL

### Status Dokumen

| Atribut | Nilai |
|---|---|
| **Document Status** | `ACTIVE` — Enterprise Ready |
| **Current Version** | `1.0.0` |
| **Last Review** | 2026-06-29 |
| **Next Review** | 2026-09-29 (kuartalan, atau lebih awal bila ada perubahan arsitektur/finansial) |
| **Approval** | Menunggu sign-off **Owner / Chief Software Architect** (Human Approval, §24.5) |

### Catatan v1.1 (Ditangguhkan)

Tiga topik yang sempat direncanakan ditangguhkan ke **v1.1** dan **belum** tercakup pada v1.0 ini: **Design Principles** (SOLID/SoC/DRY/KISS dll. sebagai bab tersendiri), **Architectural Decision Records (ADR)** terkonsolidasi (catatan: ADR nyata sudah ada di repo pada `ARCHITECTURE_DECISION.md` & `CONTENT_ARCHITECTURE_DECISION.md`), dan **Non-Functional Requirements (NFR)** formal. Penambahannya mengikuti versioning §G.5 (MINOR).

### Change Log

| Versi | Tanggal | Perubahan |
|---|---|---|
| 0.1.0 | 2026-06-29 | Bagian I (Foundation) disusun. |
| 0.2.0 | 2026-06-29 | Metadata diperkaya; bab Document Governance + diagram rantai ditambahkan; Bagian II (Engineering). |
| 0.3.0 | 2026-06-29 | Bagian III (Development). |
| 0.4.0 | 2026-06-29 | Bagian IV (AI). |
| **1.0.0** | 2026-06-29 | Bagian V (Governance) + Closing + Lampiran A–F + Completion Report. Status **ENTERPRISE READY**. |

### Change Management Reminder

> ⚠️ Dokumen ini ber-status `ACTIVE`. **Setiap perubahan wajib melalui Change Request** (§G.3–§G.4), menaikkan versi (§G.5), dan mencatat Change Log. Tidak ada perubahan langsung tanpa jejak. **Dokumentasi usang dianggap cacat** dan harus diperbaiki sebagai bagian dari Definition of Done.

---
---

# APPENDIX A — GLOSSARY

| Istilah | Definisi |
|---|---|
| **DAYA Platform** | Mission-Driven Creator Economy Platform berbasis PHP Native + MySQL. |
| **Source of Truth (SoT)** | GitHub branch `main`; kode berlaku bila konflik dengan dokumen. |
| **Modular Monolith** | Satu basis kode terorganisir menjadi modul independen (bukan microservices). |
| **Bounded Context** | Batas konseptual sebuah domain bisnis (mis. BC-IAM, BC-CNT, BC-COL). |
| **Aggregate Root** | Entity utama yang menjaga konsistensi anggota aggregate (mis. `Content`, `Wallet`). |
| **User** | Identitas dasar; induk dari role Creator/Audience/Affiliate. |
| **Creator** | Role specialization yang memproduksi karya & berhak monetisasi. |
| **Content** | Aggregate root tunggal seluruh karya; jenis dibedakan oleh `content_type`. |
| **Content Part** | Sub-unit terurut Content (bab/episode); diakses via Content. |
| **Collection** | Container terurut milik creator untuk banyak Content. |
| **content_type** | Taksonomi jenis karya (Story, Novel, Artikel, Audio, dll.). |
| **Ledger** | Sumber kebenaran finansial: append-only, immutable, double-entry. |
| **Minor Unit** | Satuan uang terkecil disimpan sebagai integer (BIGINT), mis. sen/rupiah. |
| **Wallet** | Aggregate root saldo & Credit; saldo diturunkan dari ledger. |
| **Credit** | Saldo/kredit internal anggota aggregate Wallet. |
| **Mission Score** | Skor kontribusi dampak misi seorang creator. |
| **Foundation** | Entity governance penerima nilai via Revenue Sharing/Sponsor. |
| **Revenue Sharing** | Pembagian nilai transaksi antar creator/platform/affiliate/misi. |
| **Configuration Engine** | Mekanisme membuat aturan bisnis dapat dikonfigurasi tanpa ubah kode. |
| **RBAC** | Role-Based Access Control (role + permission, least privilege). |
| **CreatorHandle / Slug** | Pengenal publik unik untuk URL SEO-friendly. |
| **ModuleManager** | Komponen auto-discovery config/route/lifecycle modul. |
| **Thin Controller / Rich Service** | Controller mengatur alur; business logic di service. |
| **Repository Pattern** | Pemisahan akses data dari business logic. |
| **DoR / DoD** | Definition of Ready / Definition of Done. |
| **Planned Standard** | Aturan yang diusulkan namun belum ada/diimplementasi di repository. |

---

# APPENDIX B — MASTER DOCUMENT MATRIX

> Status mengacu kondisi repository terindeks. `Repo` = ada di repo; `Pack` = di Developer Pack; `Kit` = di TRAE Project Kit.

| Dokumen | Lokasi | Peran | Status |
|---|---|---|:--:|
| `PROJECT_BIBLE.md` | `docs/` | Handbook & governance (dokumen ini) | 🟡 v1.0 (Design/ADR/NFR → v1.1) |
| `README.md` | root | Orientasi & status implementasi | ✅ `Repo` |
| `README_DEVELOPER.md` | `docs/` | Panduan developer baru | ✅ `Repo` |
| `CODING_STANDARD.md` | `docs/` | Standar penulisan kode | ✅ `Repo` |
| `MODULE_TEMPLATE.md` | `docs/` | Struktur baku modul | ✅ `Repo` |
| `PROJECT_GUIDELINE.md` | `docs/` | Panduan kerja implementasi | ✅ `Repo` |
| `ARCHITECTURE_DECISION.md` | `docs/` | 10 keputusan arsitektur baseline (ADR nyata) | ✅ `Repo` |
| `CONTENT_ARCHITECTURE_DECISION.md` | `docs/` | 8 keputusan arsitektur Content (ADR nyata) | ✅ `Repo` |
| `PROJECT_CONSTITUTION.md` | `DEVELOPER_PACK/` | Hukum tertinggi proyek | ✅ `Pack` |
| `MASTER_BLUEPRINT.md` | `DEVELOPER_PACK/` | Peta seluruh dokumentasi (Frozen v1.0) | 🔒 `Pack` |
| `DOMAIN_MODEL.md` | `DEVELOPER_PACK/` | 21 entity / 7 bounded context | ✅ `Pack` |
| `IMPLEMENTATION_GUIDE.md` | `DEVELOPER_PACK/` | Panduan implementasi menyeluruh | ✅ `Pack` |
| Standar (CODING/FOLDER/DB/API/SECURITY/UI) | `DEVELOPER_PACK/` | Standar rinci | ✅ `Pack` |
| `IMPLEMENTATION_SEQUENCE.md` | `TRAE_PROJECT_KIT/` | Roadmap 14 phase | ✅ `Kit` |
| `PROMPTS/*` | `TRAE_PROJECT_KIT/` | Prompt per phase | ✅ `Kit` |
| `<modul>/{README,BUSINESS_RULES,FLOW,DATABASE,API,UI,...}.md` | `app/modules/<modul>/` | Dokumen modul | bervariasi |

---

# APPENDIX C — REPOSITORY STRUCTURE

> Tree berdasarkan struktur terverifikasi di repository. Item bertanda `(*)` adalah konvensi standar yang mungkin kosong/menyusul.

```
DAYA Platform Enterprise/
├── public/                         # web root tunggal
│   ├── index.php                   # front controller
│   ├── .htaccess (*)
│   └── assets/ (*)
├── app/
│   ├── config/
│   │   ├── bootstrap.php
│   │   ├── app.php
│   │   ├── database.php
│   │   └── routes.php
│   ├── core/
│   │   ├── Application.php
│   │   ├── Router.php
│   │   ├── ModuleManager.php
│   │   ├── Autoloader.php
│   │   ├── Config.php · Env.php · ErrorHandler.php
│   │   ├── Http/ (Request, Response, SessionManager)
│   │   ├── Logging/ (Logger)
│   │   └── Modular/ (BaseModule/Controller/Service/Repository/Model/Request/Response)
│   ├── middleware/                 # Auth, RBAC, CSRF, RateLimit
│   ├── helpers/ (functions.php)
│   └── modules/
│       ├── Authentication/         # ✅ implemented
│       ├── Creator/                # ✅ implemented
│       ├── Content/                # ✅ implemented
│       └── Collection/             # ✅ implemented
├── database/
│   ├── migrations/                 # SQL bernomor timestamp
│   │   ├── 20260628_000001_create_authentication_module.sql
│   │   ├── 20260629_000002_create_creator_module.sql
│   │   ├── ... (content)
│   │   └── 20260629_000005_create_collection_module.sql
│   └── seeders/                    # seed permission, dll.
├── docs/                           # standar + PROJECT_BIBLE.md
├── DAYA_PLATFORM_DEVELOPER_PACK/   # blueprint & spesifikasi target
├── TRAE_PROJECT_KIT/               # prompt & sequence implementasi
├── storage/ (*)                    # logs, uploads, cache, config/.env
└── README.md
```

---

# APPENDIX D — MODULE STATUS MATRIX

> **Kolom Status Kode = acuan utama** (Source of Truth). Status dokumen disertakan untuk transparansi rekonsiliasi.

| Modul | Status Kode | Status Dokumen (Pack/Kit) | Klasifikasi |
|---|:--:|:--:|:--:|
| Authentication & RBAC | ✅ Implemented | 🔴 DRAFT (pack) | **Implemented** |
| Creator | ✅ Implemented | 🟡 DOMAIN | **Implemented** |
| Content | ✅ Implemented | 🟡 DOMAIN | **Implemented** |
| Collection | ✅ Implemented | — (tidak di pack) | **Implemented** |
| Admin Dashboard | 🟡 Sebagian (monitoring per modul) | 🔴 DRAFT | **In Progress** |
| Content Part | 🟡 Sub-entitas Content | 🔴 DRAFT | **In Progress** |
| Wallet | 🔴 Belum berkode | ✅ READY (pilot) | **Planned** |
| Payment (Duitku) | 🔴 Belum berkode | 🔴 DRAFT | **Planned** |
| Affiliate | 🔴 Belum berkode | 🔴 DRAFT | **Planned** |
| Notification | 🔴 Belum berkode | 🔴 DRAFT | **Planned** |
| Analytics | 🔴 Belum berkode | 🔴 DRAFT | **Planned** |
| Sponsor | 🔴 Belum berkode | 🔴 DRAFT | **Future** |
| Foundation | 🔴 Belum berkode | 🔴 DRAFT | **Future** |
| Mission Allocation | 🔴 Belum berkode | 🔴 DRAFT | **Future** |

> **Sorotan rekonsiliasi.** Wallet ✅ READY di dokumen namun **belum berkode**; Authentication 🔴 DRAFT di dokumen namun **sudah berkode**. Bila terjadi konflik, **Status Kode yang berlaku**.

---

# APPENDIX E — CROSS REFERENCE MATRIX

| Dari | Ke | Hubungan |
|---|---|---|
| `PROJECT_BIBLE` | `PROJECT_CONSTITUTION` | Bible mengikuti & merangkum Constitution (hukum tertinggi). |
| `PROJECT_BIBLE` | `MASTER_BLUEPRINT` | Bible memetakan struktur dari Blueprint (Frozen). |
| `PROJECT_BIBLE` | `DOMAIN_MODEL` | Bible memakai ubiquitous language Domain Model. |
| `PROJECT_BIBLE §13` | `CODING_STANDARD` | Standar coding kanonik. |
| `PROJECT_BIBLE §14` | `MODULE_TEMPLATE` | Struktur & dokumen wajib modul. |
| `PROJECT_BIBLE §10` | `ARCHITECTURE_DECISION` · `CONTENT_ARCHITECTURE_DECISION` | Keputusan arsitektur (ADR nyata). |
| `PROJECT_BIBLE §22` | `<modul>/DEPLOYMENT_NOTES` | Urutan & langkah deploy modul. |
| `PROJECT_BIBLE §29` | `<modul>/BUSINESS_RULES` | Katalog aturan kanonik per modul. |
| `PROJECT_BIBLE Bagian III` | `IMPLEMENTATION_SEQUENCE` | Urutan 14 phase implementasi. |
| `<modul>/DATABASE.md` | `database/migrations/*` | Skema ↔ migrasi nyata. |

---

# APPENDIX F — DOCUMENTATION PRIORITY

| Prioritas | Dokumen | Alasan |
|:--:|---|---|
| **CRITICAL** | `PROJECT_BIBLE.md`, `PROJECT_CONSTITUTION.md`, `MASTER_BLUEPRINT.md` | Pedoman & hukum tertinggi; wajib dibaca semua kontributor. |
| **HIGH** | `DOMAIN_MODEL.md`, `CODING_STANDARD.md`, `MODULE_TEMPLATE.md`, `SECURITY_STANDARD.md`, `DATABASE_STANDARD.md` | Menentukan kebenaran model & standar implementasi. |
| **MEDIUM** | `ARCHITECTURE_DECISION.md`, `CONTENT_ARCHITECTURE_DECISION.md`, `IMPLEMENTATION_GUIDE.md`, `IMPLEMENTATION_SEQUENCE.md`, `FOLDER_STRUCTURE.md`, `API_STANDARD.md`, `UI_STANDARD.md` | Keputusan & panduan operasional. |
| **LOW** | `README.md`, `README_DEVELOPER.md`, `PROJECT_GUIDELINE.md`, dokumen modul individual | Orientasi & detail per modul (penting, namun turunan). |

---
---

# PROJECT BIBLE — COMPLETION REPORT

| Metrik | Nilai |
|---|---|
| **Total Bab** | 33 (Bab 1–33) + Front Matter (Metadata, Source of Truth, Document Governance) + Closing |
| **Total Bagian** | 5 (Foundation, Engineering, Development, AI, Enterprise Governance) |
| **Total Lampiran** | 6 (Appendix A–F) |
| **Total Diagram (ASCII)** | ≈ 25 (rantai governance, request lifecycle, workflow Git/Dev/Test/Deploy/Release, AI collaboration, memory hierarchy, revenue flow, timeline, dll.) |
| **Total Checklist** | ≈ 30 blok checklist implementasi & kriteria |
| **Total Cross Reference** | ≈ 60+ rujukan antar-bab & antar-dokumen |
| **Total Standards (Repo)** | Coding, Folder, Database, Security, UI, Module, Git, Development, Documentation, Testing, Deployment, Release |
| **Total Planned Standards** | Commit Convention terstruktur, Code Review checklist formal, DoR formal, CI/automated testing, AI Prompt/Handover/Memory Standard, RACI formal, Gemini role, Revenue split final, Design Principles/ADR/NFR (v1.1) |
| **Version** | `1.0.0` |
| **Status** | `ACTIVE — ENTERPRISE READY` |

```
╔══════════════════════════════════════════════════════════╗
║                                                          ║
║                   PROJECT_BIBLE v1.0                     ║
║                                                          ║
║                STATUS: ENTERPRISE READY                  ║
║                                                          ║
║   DAYA Platform Enterprise — Engineering Handbook        ║
║   Source of Truth: GitHub (branch main)                  ║
║   © Java Maya Official                                    ║
║                                                          ║
╚══════════════════════════════════════════════════════════╝
```

> **— Akhir PROJECT_BIBLE v1.0 —**
