# FOLDER STRUCTURE — DAYA PLATFORM

> Struktur folder wajib. Hanya `public/` yang terekspos web. Turunan dari PROJECT_CONSTITUTION §8.2.

## 1. STRUKTUR PROYEK
```
daya-platform/
├── public/                  # Web root (satu-satunya yang terekspos)
│   ├── index.php            # Front controller
│   ├── .htaccess            # Routing & security
│   └── assets/              # css, js, img (Bootstrap 5, vanilla JS)
├── app/
│   ├── config/              # Konfigurasi & bootstrap
│   ├── core/                # Router, Request, Response, Container, DB
│   ├── middleware/          # Auth, RBAC, CSRF, RateLimit
│   ├── modules/             # Modul independen (lihat di bawah)
│   └── helpers/             # Utilitas reusable
├── storage/                 # Di luar web root
│   ├── logs/
│   ├── uploads/
│   └── cache/
├── database/
│   ├── migrations/          # File SQL bernomor (cPanel/phpMyAdmin)
│   └── seeds/
├── docs/                    # Dokumentasi (DAYA-NN-CODE-*.md)
└── README.md
```

## 2. STRUKTUR INTERNAL MODUL
```
app/modules/<modul>/
├── controllers/   # Thin — mengatur alur request/response
├── services/      # Rich — business logic & business rules
├── models/        # Representasi & akses data (repository)
├── views/         # Tampilan (Bootstrap 5, mobile-first)
└── routes.php     # Definisi route modul
```

## 3. ATURAN
- Kode aplikasi **tidak boleh** di dalam `public/`.
- Konfigurasi & rahasia **di luar** web root.
- Penamaan folder/file mengikuti CODING_STANDARD.
- Setiap modul mengikuti struktur internal yang sama (konsistensi).
