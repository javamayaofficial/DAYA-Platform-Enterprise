# API / ROUTES

## Creator Self Service
- `GET /collection` : dashboard Collection creator.
- `GET /collection/create` : form buat Collection.
- `POST /collection/create` : simpan Collection baru.
- `GET /collection/{id}` : detail Collection milik creator.
- `GET /collection/{id}/edit` : form edit Collection.
- `POST /collection/{id}/edit` : simpan perubahan Collection.
- `POST /collection/{id}/items` : tambah Content ke Collection.
- `POST /collection/{id}/items/reorder` : ubah urutan item Collection.
- `POST /collection/{id}/items/{itemId}/delete` : hapus item dari Collection.
- `POST /collection/{id}/publish` : publish Collection.
- `POST /collection/{id}/draft` : kembalikan Collection ke draft.
- `POST /collection/{id}/delete` : soft delete Collection.

## Public
- `GET /collections` : list Collection published.
- `GET /collections/{slug}` : detail halaman publik Collection.

## Admin
- `GET /collection/admin` : directory Collection admin.
- `GET /collection/admin/{id}` : detail Collection admin.
