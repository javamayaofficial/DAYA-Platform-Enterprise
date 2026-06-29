# DEVELOPMENT NOTES

## Arsitektur
- Controller tetap tipis dan mendorong orchestration ke `CreatorService`.
- Query list/search/pagination dienkapsulasi lewat `CreatorSearchCriteria`.
- Transform output view memakai `CreatorResource` agar controller tidak memformat data mentah.
- Authorization own/admin dipusatkan di `CreatorPolicy`.
- Identity digital diperluas melalui migration tambahan dan tabel turunan, bukan dengan perubahan core architecture.
- Statistik finansial di Creator diperlakukan sebagai snapshot/projection, bukan sumber kebenaran ledger.

## Reuse
- Reuse `Authentication` untuk role assignment `creator` dan permission admin review.
- Reuse middleware global `AuthMiddleware` dan `CsrfMiddleware`.
- Reuse `Base*` modular foundation tanpa perubahan pada core.
