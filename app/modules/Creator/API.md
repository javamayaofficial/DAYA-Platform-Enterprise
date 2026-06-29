# API / ROUTES

## Self Service
- `GET /creator` : dashboard Creator.
- `GET /creator/register` : form pendaftaran Creator.
- `POST /creator/register` : submit pendaftaran Creator.
- `GET /creator/profile` : detail profil Creator sendiri.
- `GET /creator/profile/edit` : form edit profil.
- `POST /creator/profile/edit` : simpan perubahan profil.
- `POST /creator/profile/settings` : update pengaturan publik.
- `POST /creator/profile/social-links` : tambah social link.
- `POST /creator/profile/social-links/{id}/delete` : hapus social link.
- `POST /creator/profile/portfolio` : tambah portfolio.
- `POST /creator/profile/portfolio/{id}/delete` : hapus portfolio.
- `POST /creator/profile/achievements` : tambah achievement.
- `POST /creator/profile/achievements/{id}/delete` : hapus achievement.
- `POST /creator/profile/delete` : soft delete Creator.

## Public
- `GET /creators` : list public creator dengan search dan pagination.
- `GET /creators/{handle}` : detail halaman publik creator.

## Admin
- `GET /creator/admin` : directory creator untuk admin.
- `GET /creator/admin/{id}` : detail creator untuk admin.
- `POST /creator/admin/{id}/review` : approval/reject/suspend/revoke creator.
