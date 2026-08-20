---
name: coding-standards
description: Standar coding, arsitektur Clean Code, dan konvensi Laravel 12 untuk project Sistem Kas.
---

# Coding Standards & Clean Code Guideline

## 1. Naming & Style Conventions
- **Classes**: PascalCase (`CashTransactionService`, `StoreTransactionRequest`, `CashCategoryPolicy`).
- **Methods**: camelCase (`createTransaction()`, `calculateBalance()`).
- **Variables & Properties**: camelCase (`$transactionDate`, `$cashCategory`).
- **Database Tables & Columns**: snake_case (`cash_transactions`, `cash_category_id`, `created_by`).
- **Routes**: kebab-case or dot-notation names (`modules.cash.transactions.index`).
- **Views**: kebab-case (`app-modules.blade.php`, `index.blade.php`).

## 2. Layering Rules
- **Controllers**: Thin HTTP layer only. Do not put business logic or complex queries in controllers.
- **Form Requests**: All incoming user input must be validated via FormRequest.
- **Services**: All business transactions and operations must reside in Services. Always wrap multi-step DB changes in `DB::transaction()`.
- **Repositories**: Isolates complex DB queries, aggregate calculations, and filters.
- **Policies**: All authorization must be checked via Policy.
- **Enums**: Always use PHP Enums for status, roles, and types instead of magic strings.

## 3. UI Requirements
- Every form input must have an icon group and a descriptive placeholder.
- Every photo upload form must have live image preview with reset option.
- Every button must have an icon group and descriptive text (except table action buttons which are icon-only with tooltips).
- Every table must have automatic row numbering with header `#` or `No`.
