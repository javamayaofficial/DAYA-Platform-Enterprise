# ARCHITECTURE DECISION

## Tujuan
- Menjelaskan keputusan arsitektur baseline yang dipakai oleh `DAYA Platform Framework v1.0`.
- Menjadi acuan sebelum pembangunan module berikutnya seperti `Creator`.

## Decision 1: Modular Monolith
- Keputusan: proyek menggunakan `modular monolith`.
- Alasan:
  - Cocok untuk shared hosting.
  - Tetap memberi batas modul yang jelas.
  - Menghindari kompleksitas microservices terlalu dini.

## Decision 2: PHP Native 8.x + MySQL
- Keputusan: framework dibangun di atas PHP Native dan MySQL.
- Alasan:
  - Deployable di cPanel/FastPanel tanpa build pipeline berat.
  - Mudah dipelihara dan dipindahkan.
  - Sesuai constraint `shared hosting compatibility`.

## Decision 3: Front Controller + Router + Middleware
- Keputusan: semua request masuk melalui `public/index.php`, diteruskan ke bootstrap, router, dan middleware.
- Alasan:
  - Entry point tunggal mempermudah security hardening.
  - Middleware global dapat diterapkan konsisten.
  - Struktur ini cukup sederhana namun scalable.

## Decision 4: Thin Controller, Rich Service
- Keputusan: controller menangani orchestration HTTP, service memegang business logic.
- Alasan:
  - Memudahkan testing dan refactoring.
  - Mengurangi duplikasi logic.
  - Menjaga modul tetap maintainable saat bertambah besar.

## Decision 5: Repository Pattern
- Keputusan: akses data dipisah ke repository.
- Alasan:
  - SQL tidak bercampur dengan business logic.
  - Mudah mengganti pola query tanpa menyentuh controller/service.
  - Konsisten dengan pola modul enterprise.

## Decision 6: Reusable Modular Base Layer
- Keputusan: baseline framework menyediakan `BaseModule`, `BaseController`, `BaseService`, `BaseRepository`, `BaseModel`, `BaseRequest`, dan `BaseResponse`.
- Alasan:
  - Semua modul baru dapat mengikuti pola yang sama.
  - Mengurangi boilerplate.
  - Menjaga konsistensi implementasi lintas module.

## Decision 7: Module Auto-Discovery
- Keputusan: `ModuleManager` memuat config, route, dan lifecycle modul berdasarkan konvensi folder/nama class.
- Alasan:
  - Modul baru tidak memerlukan wiring manual khusus.
  - Baseline framework lebih scalable.
  - Risiko hardcode dependency antar modul berkurang.

## Decision 8: Shared Hosting First
- Keputusan: semua keputusan framework harus aman dijalankan tanpa Docker, queue worker wajib, SSH, atau bundler.
- Alasan:
  - Sesuai target lingkungan produksi awal.
  - Menjaga barrier operasional tetap rendah.
  - Mendorong desain yang sederhana dan tahan lama.

## Decision 9: Configuration Driven
- Keputusan: konfigurasi global di `app/config/`, konfigurasi modul di `app/modules/<ModuleName>/config/`.
- Alasan:
  - Memudahkan override dan pemisahan concern.
  - Menjaga dependency modul tetap lokal.
  - Mengurangi hardcoded values.

## Decision 10: Dokumentasi Sebagai Baseline Resmi
- Keputusan: seluruh standar framework disimpan di `docs/`.
- Alasan:
  - Memudahkan onboarding developer dan AI.
  - Menjaga keputusan arsitektur tetap eksplisit.
  - Menjadi acuan sebelum ekspansi modul baru.

## Decision 11: Content Sebagai Aggregate Root Seluruh Karya
- Keputusan: seluruh karya platform berasal dari satu entitas utama `Content`, dengan klasifikasi melalui `content_type`.
- Dokumen resmi keputusan ini dibekukan pada `docs/CONTENT_ARCHITECTURE_DECISION.md`.
- Konsekuensi:
  - `Story`, `Novel`, `Cerpen`, `Artikel`, `Audio`, `Podcast`, `Ebook`, dan jenis karya lain diperlakukan sebagai `content_type`.
  - Fitur lintas domain seperti SEO, analytics, views, likes, comments, shares, sponsor, donation, affiliate, recommendation, search, wallet revenue, notification, dan integrasi lain harus mengacu ke `content_id`.
  - Relasi kepemilikan karya adalah `Creator -> many Content`.

## Konsekuensi Positif
- Framework siap menjadi baseline reusable.
- Pola implementasi modul sudah stabil.
- Authentication sudah membuktikan fondasi modular bisa dipakai.

## Trade-Off Yang Disadari
- Belum ada event bus resmi.
- Belum ada container/DI framework formal.
- Belum ada CLI tooling internal.
- Semua trade-off ini disengaja agar framework tetap ringan dan shared-hosting friendly pada v1.0.

## Status
- Keputusan arsitektur ini dianggap `frozen baseline` untuk fase setelah `Foundation Freeze`.
