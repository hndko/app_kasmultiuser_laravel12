---
name: tdd-workflow
description: Test-Driven Development and verification tests for Laravel services and endpoints.
---

# TDD Workflow Guideline

1. Tulis skenario pengujian unit untuk Services (misal: `CashBalanceService`, `CashTransactionService`).
2. Tulis feature tests untuk HTTP endpoints, Form Requests, dan Policies.
3. Jalankan `php artisan test` secara berkala.
