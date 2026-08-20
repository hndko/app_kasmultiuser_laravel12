---
name: database-migrations
description: Best practices for database schema design, migrations, indexing, and soft deletes.
---

# Database Migrations Guideline

1. **Foreign Keys**: Gunakan constraint foreign key yang jelas (`foreignId()->constrained()`).
2. **Indexing**: Tambahkan index pada kolom yang sering difilter/di-search (`transaction_date`, `type`, `cash_category_id`, `created_by`, `transaction_number`).
3. **Soft Deletes**: Gunakan `$table->softDeletes()` untuk `cash_transactions` dan `cash_categories`.
4. **Data Integrity**: Jangan izinkan penghapusan permanen jika data berelasi dengan histori transaksi penting.
