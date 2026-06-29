# CODING STANDARD

## Tujuan
- Dokumen ini menjadi standar implementasi resmi untuk `DAYA Platform Framework v1.0`.
- Seluruh modul baru wajib mengikuti aturan pada dokumen ini.
- Bila terjadi konflik dengan dokumen lain, ikuti `PROJECT_CONSTITUTION.md` lalu dokumen ini.

## Naming Convention
| Elemen | Konvensi | Contoh |
|---|---|---|
| Class | `PascalCase` | `CreatorService` |
| File class | Sama dengan class | `CreatorService.php` |
| Method | `camelCase` | `createCreatorProfile()` |
| Variabel | `camelCase` | `$creatorId` |
| Konstanta | `UPPER_SNAKE_CASE` | `DEFAULT_PAGE_SIZE` |
| Tabel database | `snake_case` jamak | `creator_profiles` |
| Kolom database | `snake_case` | `email_verified_at` |
| Route | `kebab-case` | `/creator-dashboard` |
| View file | `snake_case` | `profile_card.php` |
| Migration file | `snake_case` + timestamp | `20260628_000001_create_authentication_module.sql` |

## Folder Convention
- `public/` hanya untuk front controller, installer, dan asset publik.
- `app/config/` untuk bootstrap, konfigurasi global, dan route global.
- `app/core/` untuk fondasi framework reusable.
- `app/core/Modular/` untuk abstraksi base class seluruh modul.
- `app/middleware/` untuk middleware global lintas modul.
- `app/modules/<ModuleName>/` untuk satu bounded module yang self-contained.
- `storage/` untuk log, upload, cache, dan file runtime non-public.
- `database/migrations/` untuk SQL migration yang compatible dengan phpMyAdmin/shared hosting.
- `docs/` untuk seluruh dokumen baseline framework dan keputusan arsitektur.

## Namespace Convention
- Namespace root aplikasi adalah `App\`.
- Core framework memakai `App\Core\...`.
- Base modular framework memakai `App\Core\Modular\...`.
- Global middleware memakai `App\Middleware\...`.
- Modul memakai `App\Modules\<ModuleName>\...`.
- Nama folder modul dan nama class module utama harus identik, mis. folder `Authentication` memakai namespace `App\Modules\Authentication\...`.

## Service Convention
- Service memegang business logic dan orchestration use case.
- Service wajib setipis mungkin terhadap HTTP; request parsing bukan tanggung jawab service.
- Service baru sebaiknya mewarisi `BaseService` bila butuh akses config module.
- Service tidak merender view dan tidak mengakses `$_POST`, `$_GET`, atau `$_SESSION` secara langsung.
- Service boleh berkomunikasi dengan repository lain selama dependency-nya eksplisit di constructor.

## Repository Convention
- Repository hanya bertanggung jawab pada akses data.
- Repository tidak boleh memuat business rule yang kompleks.
- Repository baru sebaiknya mewarisi `BaseRepository`.
- Seluruh query wajib memakai prepared statements atau query aman yang ekuivalen.
- Repository mengembalikan model, array DTO, atau scalar sederhana secara konsisten sesuai kebutuhan use case.

## Controller Convention
- Controller harus `thin controller`.
- Controller hanya menerima `Request`, memanggil validator/request wrapper, service, lalu mengembalikan response.
- Controller tidak boleh menulis SQL.
- Controller modul sebaiknya mewarisi `BaseController` atau abstraksi turunan modul.
- Flash message, redirect, dan render view harus lewat response/helper yang konsisten.

## Response Convention
- HTML response menggunakan wrapper response modul atau `BaseResponse`.
- Redirect harus konsisten memakai helper response, bukan header manual.
- JSON response wajib memakai amplop yang konsisten:

```json
{
  "success": true,
  "data": {},
  "message": "OK"
}
```

- Response tidak boleh mengandung business rule; hanya format hasil akhir.

## Error Handling
- Gunakan `ErrorHandler` global sebagai fallback sistem.
- Exception tidak boleh ditelan diam-diam.
- Error teknis detail hanya ditampilkan saat debug aktif.
- Error operasional harus menghasilkan response yang aman, jelas, dan dapat dicatat ke log.
- Untuk operasi sensitif, log minimal harus menyimpan pesan, file, line, dan konteks penting non-rahasia.

## Validation Standard
- Semua input wajib divalidasi di server-side.
- Validasi dapat diletakkan di request wrapper atau validator service khusus.
- Error validasi harus dikembalikan dalam bentuk struktur yang konsisten:

```php
[
    'errors' => ['email' => 'Email tidak valid.'],
    'data' => [...]
]
```

- Normalisasi input seperti trim, lowercase email, casting integer/boolean dilakukan sebelum business logic utama.

## Security Standard
- Password wajib memakai `password_hash()` dan diverifikasi dengan `password_verify()`.
- Semua form mutasi state wajib memakai CSRF token.
- Login dan endpoint sensitif wajib dilindungi rate limit bila relevan.
- Session harus diregenerasi setelah login.
- Cookie sensitif wajib `httponly` dan mengikuti `same_site` config.
- Secret dan environment tidak boleh di-hardcode di source code.
- SQL injection dicegah dengan prepared statements.
- RBAC wajib dipakai untuk area admin dan route sensitif.
- Hanya `public/` yang boleh menjadi web root.

## Shared Hosting Standard
- Tidak boleh ada dependency wajib ke Docker, NodeJS, daemon, queue worker, atau SSH.
- Seluruh migration harus dapat dijalankan manual via phpMyAdmin/import SQL.
- Asset harus dapat dipakai tanpa build tool.
- Semua fallback harus aman jika hanya tersedia PHP Native + MySQL + cron panel.

## Definition Of Good Module Code
- Mengikuti pola `Module -> Controller -> Request -> Service -> Repository -> Model -> Response`.
- Tidak ada akses global state yang tidak perlu.
- Tidak ada duplikasi render path, config key, dan response flow.
- Mudah dipindahkan ke modul baru tanpa copy-paste business logic.
