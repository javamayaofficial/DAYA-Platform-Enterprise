# WALLET MODULE — README

> **Status: ✅ IMPLEMENTATION READY** — Modul pilot & **reference implementation** untuk seluruh modul lain.

## METADATA
| Atribut | Nilai |
|---|---|
| Modul | Wallet (Financial Core) |
| Bounded Context | BC-FIN |
| Status | ✅ IMPLEMENTATION READY |

## TUJUAN
Menjaga kebenaran nilai (financial truth) platform melalui ledger double-entry yang append-only & immutable. Setiap pergerakan uang tercatat & dapat diaudit.

## ENTITY
Wallet (root), Credit (credit_batches), Transaction, Ledger Entry, + akun sistem (PLATFORM_REVENUE, MISSION_FUND, GATEWAY_CLEARING).

## REFERENSI
- Domain: `../../_domain_reference/WALLET-DOMAIN.md`
- Model bisnis: `../../DOMAIN_MODEL.md`
- Standar: DATABASE_STANDARD, SECURITY_STANDARD, API_STANDARD.

## INDEKS DOKUMEN MODUL
1. BUSINESS_RULES.md — BR-WALLET-001..101
2. FLOW.md — F1 Provisioning … F6 Reconciliation
3. DATABASE.md — skema double-entry, ERD, DDL
4. API.md — kontrak endpoint
5. UI.md — layar & state
6. DEVELOPMENT_NOTES.md — urutan & catatan teknis
7. TESTING_CHECKLIST.md — uji fungsional/keamanan/finansial
8. DEPLOYMENT_NOTES.md — migrasi, cron, smoke test
