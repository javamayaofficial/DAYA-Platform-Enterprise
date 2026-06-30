# Story Module

Story adalah karya utama yang dikonsumsi Reader. Story dimiliki oleh Creator dan dapat (opsional) ditautkan ke Collection.

## Fitur
- Create, Edit, Preview, Duplicate, Soft Delete
- Publish Now, Schedule, Archive, Mark Review
- Public listing dan public detail by slug (hanya published + public)
- Slug otomatis dan unique slug
- Word Count dan Estimated Reading Time
- SEO metadata (title/description/canonical), Open Graph, JSON-LD placeholder

## Route
- Creator:
  - `GET /story` (dashboard)
  - `GET /story/create`
  - `POST /story/create`
  - `GET /story/{id}`
  - `GET /story/{id}/edit`
  - `POST /story/{id}/edit`
  - `GET /story/{id}/preview`
  - `POST /story/{id}/review`
  - `POST /story/{id}/publish`
  - `POST /story/{id}/schedule`
  - `POST /story/{id}/archive`
  - `POST /story/{id}/duplicate`
  - `POST /story/{id}/delete`
- Public:
  - `GET /stories`
  - `GET /stories/{slug}`
- Admin:
  - `GET /story/admin`
  - `GET /story/admin/{id}`

## Database
- Migration: `database/migrations/20260630_000001_create_story_module.sql`.
- Seeder permission: `database/seeders/20260630_000001_seed_story_permissions.sql`.

## Dokumen Modul
- `README_DEVELOPER.md`
- `BUSINESS_RULES.md`
- `FLOW.md`
- `DATABASE.md`
- `API.md`
- `TESTING_CHECKLIST.md`
- `DEPLOYMENT_NOTES.md`

