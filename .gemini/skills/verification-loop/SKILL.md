---
name: verification-loop
description: Structured verification loop for code quality, tests, and deployment readiness.
---

# Verification Loop Guideline

Setiap perubahan fitur wajib diverifikasi melalui siklus terstruktur:
1. **Lint & Syntax**: Pastikan sintaks PHP dan Blade valid.
2. **Schema & Migration**: Pastikan migrasi berjalan lancar (`php artisan migrate`) dan dapat di-rollback (`migrate:rollback`).
3. **Automated Tests**: Jalankan test suite (`php artisan test`) untuk memastikan tidak ada regresi.
4. **Manual UI Check**: Verifikasi layout, responsivitas, icon group, placeholder, photo preview, action buttons, dan penomoran tabel.
