# README DEVELOPER

## Selamat Datang
- Anda sedang bekerja di `DAYA Platform Framework v1.0`.
- Framework ini adalah baseline reusable untuk modul-modul bisnis DAYA Platform.
- Fokus framework: modular, maintainable, secure, dan compatible dengan shared hosting.

## Teknologi Utama
- PHP Native 8.x
- MySQL
- Bootstrap 5
- Vanilla JavaScript

## Struktur Penting Repo
- `public/` : web root
- `app/config/` : bootstrap dan konfigurasi global
- `app/core/` : fondasi framework
- `app/core/Modular/` : reusable base layer
- `app/modules/` : seluruh business module
- `database/migrations/` : migration SQL
- `storage/` : log, cache, upload
- `docs/` : dokumentasi resmi framework baseline

## File Yang Wajib Dibaca Developer Baru
- [README.md](file:///d:/Project/DAYA%20Platform%20Enterprise/README.md)
- [CODING_STANDARD.md](file:///d:/Project/DAYA%20Platform%20Enterprise/docs/CODING_STANDARD.md)
- [MODULE_TEMPLATE.md](file:///d:/Project/DAYA%20Platform%20Enterprise/docs/MODULE_TEMPLATE.md)
- [PROJECT_GUIDELINE.md](file:///d:/Project/DAYA%20Platform%20Enterprise/docs/PROJECT_GUIDELINE.md)
- [ARCHITECTURE_DECISION.md](file:///d:/Project/DAYA%20Platform%20Enterprise/docs/ARCHITECTURE_DECISION.md)

## Cara Memahami Framework Dengan Cepat
1. Lihat `public/index.php` untuk entry point.
2. Lihat `app/config/bootstrap.php` untuk lifecycle bootstrap.
3. Lihat `app/core/ModuleManager.php` untuk auto-discovery module.
4. Lihat `app/core/Modular/` untuk base layer reusable.
5. Lihat `app/modules/Authentication/` sebagai contoh implementasi nyata.
6. Lihat `app/modules/Creator/` sebagai contoh implementasi module bisnis kedua.

## Baseline Pola Implementasi
- Module root class: `BaseModule`
- Controller: `BaseController`
- Service: `BaseService`
- Repository: `BaseRepository`
- Model: `BaseModel`
- Request wrapper: `BaseRequest`
- Response wrapper: `BaseResponse`

## Cara Menambah Module
- Ikuti `MODULE_TEMPLATE.md`
- Ikuti `PROJECT_GUIDELINE.md`
- Gunakan `Authentication` sebagai referensi pertama
- Jangan bypass konvensi `ModuleManager`

## Hal Yang Dilarang
- Menaruh business logic di route
- Menaruh SQL di controller
- Menambah dependency produksi yang mewajibkan Docker, NodeJS, atau SSH
- Mengakses file internal modul lain secara langsung tanpa service boundary yang jelas
- Menyimpan secret ke repository

## Checklist Sebelum Commit
- Kode mengikuti `CODING_STANDARD.md`
- Struktur modul mengikuti `MODULE_TEMPLATE.md`
- Tidak mengubah business logic tanpa update dokumen modul terkait
- Route, config, dan boot module mengikuti konvensi framework
- Tidak merusak shared hosting compatibility

## Referensi Praktis
- Contoh module: [AuthenticationModule.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Authentication/AuthenticationModule.php)
- Contoh controller: [AbstractAuthenticationController.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Authentication/controllers/AbstractAuthenticationController.php)
- Contoh request: [AuthRequest.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Authentication/requests/AuthRequest.php)
- Contoh response: [AuthResponse.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Authentication/responses/AuthResponse.php)
- Contoh service: [RegistrationService.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Authentication/services/RegistrationService.php)
- Contoh repository: [UserRepository.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Authentication/models/UserRepository.php)
- Contoh model: [User.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Authentication/models/User.php)
- Contoh module bisnis: [CreatorModule.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Creator/CreatorModule.php)
- Contoh service bisnis: [CreatorService.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Creator/services/CreatorService.php)
- Contoh repository bisnis: [CreatorRepository.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Creator/models/CreatorRepository.php)
- Contoh aggregate karya: [ContentModule.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Content/ContentModule.php)
- Contoh service karya: [ContentService.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Content/services/ContentService.php)
- Contoh module container: [CollectionModule.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Collection/CollectionModule.php)
- Contoh service container: [CollectionService.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Collection/services/CollectionService.php)
- Contoh repository container: [CollectionRepository.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Collection/models/CollectionRepository.php)
- Contoh module story: [StoryModule.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Story/StoryModule.php)
- Contoh service story: [StoryService.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Story/services/StoryService.php)
- Contoh repository story: [StoryRepository.php](file:///d:/Project/DAYA%20Platform%20Enterprise/app/modules/Story/models/StoryRepository.php)
- Keputusan arsitektur karya: [CONTENT_ARCHITECTURE_DECISION.md](file:///d:/Project/DAYA%20Platform%20Enterprise/docs/CONTENT_ARCHITECTURE_DECISION.md)

## Status Framework
- Baseline framework sudah digunakan oleh module `Authentication` dan `Creator`.
- Module `Creator` kini menjadi identitas digital utama, `Content` menjadi aggregate root resmi seluruh karya, `Collection` menjadi container terurut untuk banyak Content, dan `Story` menjadi karya utama yang dikonsumsi Reader.
- Gunakan pola yang sama sebagai acuan tunggal implementasi fase berikutnya.
