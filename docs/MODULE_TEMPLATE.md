# MODULE TEMPLATE

## Tujuan
- Template ini adalah pola baku pembuatan module baru pada `DAYA Platform Framework v1.0`.
- Semua module baru seperti `Creator`, `Wallet`, `Content`, `Affiliate`, `Payment`, `Notification`, dan `Analytics` wajib mengikuti struktur ini.

## Struktur Standar Module
```text
app/modules/<ModuleName>/
├── <ModuleName>Module.php
├── README.md
├── API.md
├── BUSINESS_RULES.md
├── DATABASE.md
├── DEPLOYMENT_NOTES.md
├── DEVELOPMENT_NOTES.md
├── FLOW.md
├── TESTING_CHECKLIST.md
├── UI.md
├── routes.php
├── config/
│   └── module.php
├── controllers/
│   ├── Abstract<ModuleName>Controller.php
│   └── ...
├── services/
│   ├── ModuleBootstrap.php
│   └── ...
├── models/
│   └── ...
├── requests/
│   └── ...
├── responses/
│   └── ...
├── middleware/
│   └── ...
├── views/
│   ├── admin/
│   ├── public/
│   └── partials/
└── assets/
    ├── css/
    ├── js/
    └── img/
```

## Peran Setiap Bagian

### Module
- File utama module adalah `<ModuleName>Module.php`.
- Wajib mewarisi `BaseModule`.
- Menjadi root lifecycle module: metadata, path, config access, dan `boot()`.

### Controller
- Diletakkan di `controllers/`.
- Controller modul sebaiknya mewarisi `BaseController` atau abstract controller milik modul.
- Hanya mengatur flow request ke service dan response.

### Service
- Diletakkan di `services/`.
- Menangani business logic modul.
- Service yang butuh akses config module sebaiknya mewarisi `BaseService`.

### Repository
- Diletakkan di `models/` atau dapat dipisah ke `repositories/` jika nanti framework berkembang.
- Saat ini baseline v1.0 memakai `models/` untuk entity + repository.
- Repository sebaiknya mewarisi `BaseRepository`.

### Model
- Entity/domain object modul.
- Model sebaiknya mewarisi `BaseModel`.
- Model tidak menyimpan logic akses database.

### Request
- Diletakkan di `requests/`.
- Menjadi wrapper reusable untuk parsing dan normalisasi input.
- Request sebaiknya mewarisi `BaseRequest`.

### Response
- Diletakkan di `responses/`.
- Menjadi wrapper render/redirect/json khusus modul.
- Response sebaiknya mewarisi `BaseResponse`.

### Routes
- Semua route modul berada di `routes.php`.
- File ini didaftarkan otomatis oleh `ModuleManager`.

### Migration
- File migration SQL diletakkan di `database/migrations/`.
- Format nama: `<timestamp>_<deskripsi>.sql`.
- Satu migration boleh mewakili satu module jika masih kecil, atau dipisah jika bertambah besar.

### Views
- Diletakkan di `views/`.
- Gunakan subfolder yang mencerminkan area layar: `admin/`, `public/`, `partials/`, atau domain lain yang relevan.

### Assets
- Asset module-level diletakkan di `assets/`.
- Jika asset belum diperlukan, folder ini boleh disiapkan kosong.
- Asset tetap harus compatible dengan shared hosting tanpa build pipeline wajib.

### README
- README modul wajib menjelaskan tujuan, scope, dependency, dan indeks dokumen modul.

## Skeleton Minimal

### Module Class
```php
final class CreatorModule extends BaseModule
{
    public function __construct()
    {
        parent::__construct('Creator', base_path('app/modules/Creator'));
    }
}
```

### Controller
```php
final class CreatorController extends AbstractCreatorController
{
    public function index(Request $request): Response
    {
        return $this->render('public/index', 'Creator');
    }
}
```

### Service
```php
final class CreatorService extends BaseService
{
    public function create(array $payload): array
    {
        return ['success' => true];
    }
}
```

### Repository
```php
final class CreatorRepository extends BaseRepository
{
}
```

### Model
```php
final class CreatorProfile extends BaseModel
{
}
```

### Request
```php
final class CreatorRequest extends BaseRequest
{
}
```

### Response
```php
final class CreatorResponse extends BaseResponse
{
}
```

## Aturan Wajib
- Satu module = satu folder mandiri.
- Jangan letakkan business logic modul di `app/core/`.
- Jangan mengakses internal file modul lain secara langsung.
- Semua module baru wajib bisa di-load oleh `ModuleManager` tanpa perubahan custom per modul.
