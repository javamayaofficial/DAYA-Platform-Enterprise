# UI STANDARD — DAYA PLATFORM

> Standar UI mobile-first berbasis Bootstrap 5. Turunan dari Design Principles (#28).

## 1. PRINSIP
- **Mobile-first**: desain untuk layar kecil dulu, lalu ditingkatkan.
- **Clarity & Trust**: jelas, jujur, dan menumbuhkan kepercayaan (terutama tampilan finansial).
- **Consistency**: komponen & pola konsisten antar modul.

## 2. TEKNOLOGI
- **Bootstrap 5** (grid & komponen), tanpa build tool.
- **Vanilla JS** untuk interaksi ringan (tanpa NodeJS/bundler).
- Asset statis di `public/assets`.

## 3. RESPONSIVE
- Gunakan grid & breakpoint Bootstrap.
- Komponen dapat diakses dengan ibu jari (touch target memadai).

## 4. STATE UI WAJIB
Setiap tampilan data menangani: **loading**, **empty**, **error**, dan **success** secara eksplisit.

## 5. FORM & VALIDASI
- Validasi client-side sebagai UX pelengkap; kebenaran tetap di server.
- Pesan error jelas & ramah.

## 6. TAMPILAN UANG
- Nilai disimpan sebagai integer minor unit → **konversi saat ditampilkan** (mis. sen → rupiah) dengan format lokal.
- Jangan pernah menampilkan saldo dari sumber selain data resmi server.

## 7. AKSESIBILITAS & SEO
- Markup semantik, label & kontras memadai (a11y).
- URL SEO-friendly (kebab-case), meta dasar pada halaman publik.
