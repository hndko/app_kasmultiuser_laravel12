@extends('layouts.app-modules')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('modules.cash.transactions.index') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-brand-600 dark:text-gray-400 mb-1 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Transaksi
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Edit Transaksi: {{ $transaction->transaction_number }}
            </h1>
        </div>
    </div>

    <!-- Form Card -->
    <div
        class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 sm:p-8 shadow-xs"
        x-data="{
            currentType: '{{ old('type', $transaction->type->value) }}',
            categories: {{ Js::from($categories) }},
            selectedCategoryId: '{{ old('cash_category_id', $transaction->cash_category_id) }}',
            get filteredCategories() {
                return this.categories.filter(c => c.type === this.currentType || c.type === 'both');
            }
        }"
    >
        <form method="POST" action="{{ route('modules.cash.transactions.update', $transaction) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Type Selector Tabs -->
            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Tipe Transaksi <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <button
                        type="button"
                        @click="currentType = 'income'"
                        :class="currentType === 'income'
                            ? 'border-green-500 bg-green-50/50 text-green-700 dark:bg-green-500/10 dark:text-green-400 dark:border-green-500 font-semibold ring-2 ring-green-500/20'
                            : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/[0.02]'"
                        class="flex items-center justify-center gap-2.5 p-3.5 rounded-xl border transition cursor-pointer text-sm"
                    >
                        <span class="w-5 h-5 rounded-full bg-green-500/20 text-green-600 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                            </svg>
                        </span>
                        <span>Kas Masuk (Pemasukan)</span>
                    </button>

                    <button
                        type="button"
                        @click="currentType = 'expense'"
                        :class="currentType === 'expense'
                            ? 'border-red-500 bg-red-50/50 text-red-700 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500 font-semibold ring-2 ring-red-500/20'
                            : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/[0.02]'"
                        class="flex items-center justify-center gap-2.5 p-3.5 rounded-xl border transition cursor-pointer text-sm"
                    >
                        <span class="w-5 h-5 rounded-full bg-red-500/20 text-red-600 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                            </svg>
                        </span>
                        <span>Kas Keluar (Pengeluaran)</span>
                    </button>
                </div>
                <input type="hidden" name="type" :value="currentType" />
                @error('type')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Transaction Date Input with Icon Group & Placeholder -->
                <x-forms.input
                    type="date"
                    name="transaction_date"
                    label="Tanggal Transaksi"
                    placeholder="YYYY-MM-DD"
                    value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}"
                    required
                >
                    <x-slot:iconSlot>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </x-slot:iconSlot>
                </x-forms.input>

                <!-- Category Select filtered by type -->
                <div>
                    <label for="cash_category_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Kategori Kas <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute top-1/2 left-3.5 -translate-y-1/2 text-gray-400 pointer-events-none flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </span>
                        <select
                            name="cash_category_id"
                            id="cash_category_id"
                            required
                            class="h-11 w-full appearance-none rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent pl-11 pr-10 text-sm text-gray-800 dark:text-white/90 focus:border-brand-500 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:bg-gray-900"
                        >
                            <option value="">-- Pilih Kategori Kas --</option>
                            <template x-for="cat in filteredCategories" :key="cat.id">
                                <option :value="cat.id" :selected="selectedCategoryId == cat.id" x-text="cat.name + ' (' + cat.code + ')'"></option>
                            </template>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </div>
                    @error('cash_category_id')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Amount Input with Icon Group & Placeholder -->
                <x-forms.input
                    type="number"
                    step="any"
                    name="amount"
                    label="Nominal (Rupiah)"
                    placeholder="cth. 500000"
                    value="{{ old('amount', (int)$transaction->amount) }}"
                    help="Masukkan angka tanpa titik atau koma"
                    required
                >
                    <x-slot:iconSlot>
                        <span class="font-bold text-xs text-gray-500 dark:text-gray-400">Rp</span>
                    </x-slot:iconSlot>
                </x-forms.input>

                <!-- Reference Input with Icon Group & Placeholder -->
                <x-forms.input
                    name="reference"
                    label="No. Referensi / Bukti (Opsional)"
                    placeholder="cth. KWT-001 / INV-2026/08"
                    value="{{ old('reference', $transaction->reference) }}"
                    help="Nomor kuitansi, nota, atau invoice"
                >
                    <x-slot:iconSlot>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </x-slot:iconSlot>
                </x-forms.input>
            </div>

            <!-- Description Textarea with Icon Group & Placeholder -->
            <x-forms.textarea
                name="description"
                label="Keterangan Transaksi"
                placeholder="Jelaskan rincian dan keperluan transaksi kas ini secara lengkap..."
                value="{{ old('description', $transaction->description) }}"
                rows="3"
                required
            >
                <x-slot:iconSlot>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </x-slot:iconSlot>
            </x-forms.textarea>

            <!-- Form Actions with Icon Group + Text -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                <x-buttons.secondary :href="route('modules.cash.transactions.index')">
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
                    Simpan Perubahan
                </x-buttons.primary>
            </div>
        </form>
    </div>
</div>
@endsection
