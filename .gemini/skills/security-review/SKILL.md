---
name: security-review
description: Security practices for Laravel 12 authentication, authorization, CSRF, and input sanitization.
---

# Security Review Guideline

1. **Active User Check**: User dengan status `inactive` wajib diblokir dari login dan ditolak saat session aktif menggunakan middleware `active.user`.
2. **CSRF & XSS Protection**: Pastikan seluruh form menggunakan `@csrf` dan output Blade menggunakan `{{ $var }}`.
3. **Authorization**: Selalu verifikasi hak akses melalui Policy sebelum memproses aksi create, update, atau delete.
4. **Mass Assignment**: Definisikan `$fillable` secara eksplisit pada seluruh model Eloquent.
