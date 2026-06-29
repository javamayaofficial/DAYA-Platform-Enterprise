# CHECKLIST — KEAMANAN (OWASP)

- [ ] Validasi & sanitasi input server-side.
- [ ] Prepared statements (anti SQL injection).
- [ ] Output encoding (anti XSS).
- [ ] CSRF token pada form mutasi.
- [ ] Password di-hash (`password_hash`).
- [ ] Sesi aman (regenerasi ID, httpOnly/secure).
- [ ] RBAC least privilege + cek kepemilikan resource.
- [ ] Rate limiting pada login & endpoint sensitif.
- [ ] Upload file dibatasi (tipe, ukuran, di luar web root).
- [ ] Rahasia tidak di repo/web root.
- [ ] Audit trail untuk aksi sensitif.
