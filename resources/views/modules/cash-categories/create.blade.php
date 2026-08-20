@extends('layouts.app-modules')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('modules.cash.categories.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-brand-600 dark:text-gray-400 mb-1 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Kategori
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Tambah Kategori Kas
            </h1>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 sm:p-8 shadow-xs">
        <form method="POST" action="{{ route('modules.cash.categories.store') }}" class="space-y-5">
            @csrf

            <!-- Name Input with Icon Group & Placeholder -->
            <x-forms.input
                name="name"
                label="Nama Kategori"
                placeholder="cth. Operasional Kantor / Penjualan Produk"
                value="{{ old('name') }}"
                required
            >
                <x-slot:iconSlot>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </x-slot:iconSlot>
            </x-forms.input>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Code Input with Icon Group & Placeholder -->
                <x-forms.input
                    name="code"
                    label="Kode Kategori"
                    placeholder="cth. CAT-IN-001"
                    value="{{ old('code', $suggestedCode) }}"
                    help="Kode unik untuk identifikasi kategori"
                    required
                >
                    <x-slot:iconSlot>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </x-slot:iconSlot>
                </x-forms.input>

                <!-- Type Select with Icon Group & Placeholder -->
                <x-forms.select
                    name="type"
                    label="Tipe Kategori"
                    placeholder="-- Pilih Tipe Kategori --"
                    :selected="old('type')"
                    required
                >
                    <x-slot:iconSlot>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </x-slot:iconSlot>
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}" {{ old('type') === $type->value ? 'selected' : '' }}>
                            {{ $type->label() }}
                        </option>
                    @endforeach
                </x-forms.select>
            </div>

            <!-- Description Textarea with Icon Group & Placeholder -->
            <x-forms.textarea
                name="description"
                label="Deskripsi / Catatan"
                placeholder="Penjelasan opsional mengenai kategori transaksi ini..."
                value="{{ old('description') }}"
                rows="3"
            >
                <x-slot:iconSlot>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </x-slot:iconSlot>
            </x-forms.textarea>

            <!-- Status Checkbox Toggle -->
            <div class="flex items-center gap-3 pt-2">
                <input
                    type="checkbox"
                    name="is_active"
                    id="is_active"
                    value="1"
                    {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-gray-300 dark:border-gray-700 text-brand-600 focus:ring-brand-500/20"
                />
                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300 select-none cursor-pointer">
                    Aktifkan Kategori (Dapat digunakan saat input transaksi)
                </label>
            </div>

            <!-- Form Actions with Icon Group + Text -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <x-buttons.secondary :href="route('modules.cash.categories.index')">
                    <x-slot:icon>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </x-slot:icon>
                    Batal
                </x-buttons.secondary>

                <x-buttons.primary type="submit">
                    <x-slot:icon>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </x-slot:icon>
                    Simpan Kategori
                </x-buttons.primary>
            </div>
        </form>
    </div>
</div>
@endsection
