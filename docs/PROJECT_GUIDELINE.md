# PROJECT GUIDELINE

## Tujuan
- Dokumen ini adalah panduan kerja implementasi sehari-hari untuk developer baru maupun AI contributor.
- Fokus dokumen ini adalah cara memakai baseline framework yang sudah dibangun, bukan aturan bisnis modul tertentu.

## Cara Membuat Module Baru
1. Buat folder `app/modules/<ModuleName>/`.
2. Tambahkan file root module: `<ModuleName>Module.php`.
3. Tambahkan `config/module.php`.
4. Tambahkan `routes.php`.
5. Tambahkan folder `controllers/`, `services/`, `models/`, `requests/`, `responses/`, `views/`.
6. Tambahkan dokumen modul minimum: `README.md`, `BUSINESS_RULES.md`, `FLOW.md`, `DATABASE.md`, `API.md`, `UI.md`, `DEVELOPMENT_NOTES.md`, `TESTING_CHECKLIST.md`, `DEPLOYMENT_NOTES.md`.
7. Ikuti `MODULE_TEMPLATE.md`.

## Cara Register Module
- Module baru tidak perlu didaftarkan manual di bootstrap selama mengikuti konvensi berikut:
  - Folder modul ada di `app/modules/<ModuleName>/`
  - Class module utama bernama `<ModuleName>Module`
  - Namespace `App\Modules\<ModuleName>\<ModuleName>Module`
  - Route modul ada di `app/modules/<ModuleName>/routes.php`
  - Config modul ada di `app/modules/<ModuleName>/config/module.php`
- `ModuleManager` akan memuat config, route, dan lifecycle `boot()` secara otomatis.

## Cara Membuat Migration
1. Simpan migration di `database/migrations/`.
2. Gunakan nama file timestamped, mis. `20260630_000001_create_creator_module.sql`.
3. Buat SQL yang aman untuk import manual via phpMyAdmin.
4. Hindari syntax yang terlalu bergantung pada versi DB modern jika belum dipastikan tersedia di shared hosting.
5. Update `DATABASE.md` modul sebelum migration dianggap final.

## Cara Membuat Route
- Semua route modul ditulis di `app/modules/<ModuleName>/routes.php`.
- Gunakan `$router->get()`, `$router->post()`, atau `$router->match()`.
- Route admin atau sensitif wajib memakai middleware auth/RBAC/permission.
- Route tidak boleh memuat business logic; arahkan ke controller method.

## Cara Membuat API
- API dapat tetap memakai route framework yang sama dengan response JSON.
- Gunakan wrapper response yang konsisten, mis. `BaseResponse::json()`.
- Format JSON wajib seragam:

```json
{
  "success": true,
  "data": {},
  "message": "OK"
}
```

- Validasi input tetap dilakukan di request wrapper atau validator service.

## Cara Membuat Service
- Letakkan service di `services/`.
- Gunakan `BaseService` bila service membutuhkan akses config module.
- Constructor harus eksplisit terhadap dependency seperti repository/service lain.
- Service tidak boleh langsung membaca `$_POST`, `$_GET`, `$_COOKIE`, atau `$_SESSION`.

## Cara Membuat Repository
- Letakkan repository di `models/` pada baseline v1.0.
- Gunakan `BaseRepository` untuk helper `prepare()`, `pdo()`, dan `lastInsertId()`.
- Semua akses database memakai prepared statements.
- Repository harus fokus pada persistensi dan query.

## Cara Membuat Event
- Baseline `v1.0` belum memiliki event bus/runtime dispatcher resmi.
- Untuk saat ini, event hanya boleh didesain sebagai konvensi dokumentasi atau method callback internal modul.
- Jika nanti event framework diperkenalkan, struktur yang disarankan adalah:
  - `app/modules/<ModuleName>/events/`
  - Nama event `PascalCase`, mis. `CreatorRegisteredEvent`
  - Listener ditempatkan di `services/` atau `listeners/`
- Selama belum ada dispatcher resmi, jangan membuat dependency arsitektur modul terhadap event async.

## Cara Membuat Middleware
- Middleware global letakkan di `app/middleware/`.
- Middleware modul letakkan di `app/modules/<ModuleName>/middleware/`.
- Implementasikan `App\Core\Contracts\MiddlewareInterface`.
- Middleware digunakan untuk concern lintas request seperti auth, permission, throttling, atau precondition route.

## Checklist Sebelum Module Dianggap Siap
- Struktur mengikuti `MODULE_TEMPLATE.md`
- Memakai fondasi `BaseModule`, `BaseController`, `BaseService`, `BaseRepository`, `BaseModel`, `BaseRequest`, `BaseResponse`
- Config, route, dan boot module terbaca otomatis oleh `ModuleManager`
- Migration tersedia
- Dokumentasi modul tersedia
- Tidak ada business logic yang bocor ke controller atau route
