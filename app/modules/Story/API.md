# API — Story Module

Modul ini berorientasi web (HTML response). Endpoint berikut adalah kontrak URL internal platform.

## Creator
- `GET /story` — list story milik creator.
- `GET /story/create` — form create.
- `POST /story/create` — create story.
- `GET /story/{id}` — detail story.
- `GET /story/{id}/edit` — form edit.
- `POST /story/{id}/edit` — update story.
- `GET /story/{id}/preview` — preview (private).
- `POST /story/{id}/review` — status review.
- `POST /story/{id}/publish` — publish now.
- `POST /story/{id}/schedule` — schedule publish (`published_at`).
- `POST /story/{id}/archive` — archive.
- `POST /story/{id}/duplicate` — duplicate.
- `POST /story/{id}/delete` — soft delete.

## Public
- `GET /stories` — list published+public.
- `GET /stories/{slug}` — detail published+public by slug.

## Admin
- `GET /story/admin` — list untuk admin (permission required).
- `GET /story/admin/{id}` — detail admin.

