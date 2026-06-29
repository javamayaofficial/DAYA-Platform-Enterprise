# API / ROUTES

## Creator Self Service
- `GET /content` : dashboard Content creator.
- `GET /content/create` : form buat Content.
- `POST /content/create` : simpan Content baru.
- `GET /content/{id}` : detail Content milik creator.
- `GET /content/{id}/edit` : form edit Content.
- `POST /content/{id}/edit` : simpan perubahan Content.
- `POST /content/{id}/parts` : tambah `Content Part`.
- `POST /content/{id}/parts/{partId}/delete` : hapus `Content Part`.
- `POST /content/{id}/submit` : ajukan Content ke moderasi.
- `POST /content/{id}/delete` : soft delete Content.

## Public
- `GET /contents` : list Content published.
- `GET /contents/{slug}` : detail halaman publik Content.

## Admin
- `GET /content/admin` : directory Content admin.
- `GET /content/admin/{id}` : detail Content admin.
- `POST /content/admin/{id}/review` : moderasi publish/reject/archive/remove.
