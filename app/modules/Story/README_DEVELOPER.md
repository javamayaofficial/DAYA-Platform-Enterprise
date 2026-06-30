# Story Module — Developer Notes

## Pola Framework
- Controller tipis: hanya auth session, parsing request, render view/redirect.
- Validasi: `StoryValidator`.
- Business logic: `StoryService`.
- Query DB: `StoryRepository`.
- Authorization: `StoryPolicy`.
- Dependency: dibuat via `StoryFactory` dari `StoryModule`.

## Integrasi Modul Lain
- Creator ownership: `StoryRepository::findCreatorByUserId()` mengacu ke `creator_profiles`.
- Collection (opsional): `StoryService::ensureCollectionOwnership()` memastikan `collection_id` milik creator yang sama.

## Public Availability
Public endpoint hanya menampilkan story:
- `status = published`
- `visibility = public`
- `deleted_at IS NULL`

## Slug & SEO
- Slug otomatis dibuat dari title jika kosong.
- Unique slug dijamin di service (cek `slugExists()`).
- `canonical_url` otomatis diset ke URL public saat published+public, jika kosong.

