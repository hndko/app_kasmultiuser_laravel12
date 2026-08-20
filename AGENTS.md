# AGENTS.md — Rules & Guidelines Sistem Kas Sederhana Multi-User

Dokumen ini adalah aturan baku (*guidelines & instructions*) yang **WAJIB** dipatuhi oleh seluruh developer dan agen AI dalam pengembangan project **Sistem Kas Sederhana Multi-User** di atas Laravel 12 dan template TailAdmin.

---

## 1. Prinsip Arsitektur Utama

Arsitektur aplikasi mematuhi kaidah **Clean Architecture** dan pemisahan tanggung jawab (*Separation of Concerns*):

```text
Request / HTTP Layer
       ↓
┌───────────────────────┐
│ Form Request          │ -> Validasi input data wajib via Form Request class.
└──────────┬────────────┘
           │
┌──────────▼────────────┐
│ Policy / Gate         │ -> Pengecekan otorisasi (Admin vs User biasa).
└──────────┬────────────┘
           │
┌──────────▼────────────┐
│ Controller (Thin)     │ -> Hanya menerima input, panggil Service, dan return response/redirect.
└──────────┬────────────┘
           │
┌──────────▼────────────┐
│ Service Layer         │ -> Menangani seluruh business logic, DB::transaction(), dan audit info.
└──────────┬────────────┘
           │
┌──────────▼────────────┐
│ Repository Layer      │ -> Query kompleks, filtering, agregasi income/expense, & laporan.
└──────────┬────────────┘
           │
┌──────────▼────────────┐
│ Eloquent Model & DB   │ -> Relasi, casting, soft deletes, timestamps, MySQL database.
└───────────────────────┘
```

### Aturan Kode:
1. **Thin Controller**: Controller tidak boleh memiliki business logic kompleks atau query agregasi langsung.
2. **Form Request**: Setiap endpoint yang menerima mutasi data wajib divalidasi dengan Form Request.
3. **Service Layer**: Operasi multi-langkah (misal pembuatan transaksi, generate nomor kas, validasi bisnis tipe kategori) wajib menggunakan Service dan dibungkus `DB::transaction()`.
4. **Policy**: Pengecekan hak akses tidak boleh menggunakan pengecekan `if (auth()->user()->role === 'admin')` secara hard-code di banyak controller/view. Gunakan `$this->authorize()` atau `@can()`.
5. **PHP Enums**: Nilai fixed wajib menggunakan PHP Enum (`UserRole`, `UserStatus`, `TransactionType`, `CategoryType`). Jangan gunakan *magic string*.
6. **No Database Query in Blade**: Blade view hanya menampilkan data presentation. Dilarang memanggil `\App\Models\...` di dalam Blade.
7. **Konsistensi Environment Variables**: Setiap ada penambahan, perubahan, atau penyesuaian variable pada `.env`, **WAJIB** secara otomatis memperbarui `.env.example` agar selalu sinkron dan konsisten 100%.

---

## 2. Aturan Khusus UI/UX (TailAdmin & Blade Components)

Seluruh halaman dan komponen tampilan wajib mematuhi aturan berikut tanpa pengecualian:

### 1. Form Input
- **Wajib memiliki icon group di dalam input** (sisi kiri atau kanan) dan **placeholder yang jelas & kontekstual**.
- Menampilkan pesan error validasi di bawah field input secara konsisten.

### 2. Upload Foto & Dokumen
- **Form Foto/Avatar**: Wajib memiliki **Live Preview interaktif** (langsung menampilkan gambar ketika dipilih sebelum di-submit, beserta opsi reset/clear).
- **Form Upload Berkas Lain**: Menggunakan mekanisme **Drag & Drop** (Dropzone) yang responsif.

### 3. Tombol (Buttons)
- **Tombol Form & Navigasi**: Wajib memiliki **icon group + teks label** (misal: `<svg icon> Simpan Transaksi`, `<svg icon> Tambah User`).
- **Tombol Aksi pada Tabel**: Khusus tombol di kolom aksi tabel (View, Edit, Delete) **hanya menampilkan icon** dengan atribut `title`/`tooltip` dan aria-label yang jelas.

### 4. Tabel & Penomoran
- Setiap tabel wajib memiliki kolom nomor urut otomatis dengan penamaan header `#` atau `No`.
- Penomoran wajib memperhitungkan halaman pagination:
  ```blade
  {{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}
  ```
- Setiap tabel wajib memiliki **Empty State** jika data kosong (menggunakan komponen `<x-tables.empty-state>`).

### 5. Layout Modular
- `layouts.app-auth` : Halaman Login, Register, Forgot Password, Reset Password.
- `layouts.app-modules` : Seluruh halaman internal setelah login (Dashboard, Kas, Kategori, Laporan, Users, Profil).
- `layouts.app-public` : Halaman publik.

---

## 3. Skill & Panduan dari ECC Tools (`https://ecc.tools/skills`)

Agen dan developer wajib mengacu pada panduan skill ECC Tools yang telah terpasang:
- **`coding-standards`**: Menjaga standar kode PSR-12, type hinting, return types, dan readability.
- **`verification-loop`**: Selalu memverifikasi kode, migrasi, dan routing setiap selesai menyelesaikan modul.
- **`tdd-workflow`**: Menulis unit dan feature test untuk setiap service dan alur otorisasi.
- **`database-migrations`**: Menggunakan constraint foreign key, index yang tepat, dan soft deletes.
- **`security-review`**: Memastikan CSRF protection, pencegahan XSS, session invalidation saat logout, dan pengecekan status user `active`.

---

## 4. Definition of Done (DoD) Checklist

Setiap modul atau fitur dianggap selesai bila memenuhi:
- [ ] Migration & Schema tersedia dengan index dan foreign key.
- [ ] Model & Relationship didefinisikan dengan type cast dan SoftDeletes.
- [ ] Form Request tersedia untuk validasi input.
- [ ] Policy tersedia untuk pembatasan hak akses (Admin vs User).
- [ ] Service Layer menangani workflow & DB transaction.
- [ ] Repository Layer menangani filtering & aggregate query jika diperlukan.
- [ ] Controller berada di subfolder yang benar dan tetap *thin*.
- [ ] View menggunakan layout yang tepat (`app-auth` / `app-modules`).
- [ ] Form memiliki icon group & placeholder.
- [ ] Form photo memiliki live preview.
- [ ] Tombol memiliki icon group + text (dan aksi tabel hanya icon).
- [ ] Tabel memiliki penomoran otomatis kolom `#` dan empty state.
- [ ] Test tersedia dan lulus tanpa error.

---

## 5. Standar Git Commit & Push (Conventional Commits)

Setiap penyelesaian perintah/tugas, wajib secara otomatis melakukan `git commit` dan `git push` dengan standar **Conventional Commits**:

### Format:
```text
<tipe>[cakupan opsional]: <deskripsi singkat>

[badan pesan opsional]

[catatan kaki opsional]
```

### Tipe Commit (Wajib Huruf Kecil):
- **`feat`**: Menambahkan fitur baru ke dalam kode.
- **`fix`**: Memperbaiki bug atau kesalahan kode.
- **`docs`**: Mengubah atau menambah dokumentasi (misal: `AGENTS.md`, `README.md`).
- **`style`**: Mengubah format kode tanpa mengubah fungsi (spasi, format Blade).
- **`refactor`**: Restrukturisasi kode tanpa mengubah fitur atau membenahi bug.
- **`test`**: Menambah atau memperbaiki automated tests (Pest / PHPUnit).
- **`chore`**: Konfigurasi build, dependensi, seeders, migration maintenance.

### Kaidah Penulisan:
1. **Imperative Mood**: Gunakan kalimat perintah (contoh: `add google sign-in method`, `fix category deletion bug`).
2. **Tanpa Titik di Akhir Subjek**: Jangan mengakhiri baris subjek dengan tanda titik.
3. **Maksimal 50 Karakter pada Subjek**: Baris pertama singkat, padat, dan jelas.
4. **Jarak Satu Baris Kosong**: Pisahkan subjek dan badan pesan dengan satu baris kosong jika menyertakan penjelasan tambahan.
5. **Commit Atomik**: Setiap commit berfokus pada satu hal yang telah teruji secara lokal.

