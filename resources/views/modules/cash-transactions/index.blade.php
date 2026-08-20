@extends('layouts.app-modules')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Transaksi Kas
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pencatatan dan monitoring seluruh arus kas masuk dan kas keluar.
            </p>
        </div>
        <div>
            <!-- Button with Icon Group + Text -->
            <x-buttons.primary :href="route('modules.cash.transactions.create')">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </x-slot:icon>
                Catat Transaksi Kas
            </x-buttons.primary>
        </div>
    </div>

    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Kas Masuk -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Kas Masuk (Pemasukan)</span>
                <h3 class="text-xl font-bold text-green-600 dark:text-green-400 mt-0.5">
                    {{ $summary['formatted_income'] }}
                </h3>
            </div>
        </div>

        <!-- Kas Keluar -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Kas Keluar (Pengeluaran)</span>
                <h3 class="text-xl font-bold text-red-600 dark:text-red-400 mt-0.5">
                    {{ $summary['formatted_expense'] }}
                </h3>
            </div>
        </div>

        <!-- Saldo Kas -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Saldo Kas Periode Ini</span>
                <h3 class="text-xl font-bold {{ $summary['net_balance'] >= 0 ? 'text-gray-900 dark:text-white' : 'text-red-600 dark:text-red-400' }} mt-0.5">
                    {{ $summary['formatted_balance'] }}
                </h3>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs">
        <form method="GET" action="{{ route('modules.cash.transactions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
            <!-- Keyword Search with Icon Group & Placeholder -->
            <div class="lg:col-span-2">
                <x-forms.input
                    name="search"
                    placeholder="Cari no. trx / ket / ref..."
                    value="{{ request('search') }}"
                >
                    <x-slot:iconSlot>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </x-slot:iconSlot>
                </x-forms.input>
            </div>

            <!-- Type Filter with Icon Group & Placeholder -->
            <div>
                <x-forms.select
                    name="type"
                    placeholder="-- Semua Tipe --"
                    :selected="request('type')"
                >
                    <x-slot:iconSlot>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </x-slot:iconSlot>
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}" {{ request('type') === $type->value ? 'selected' : '' }}>
                            {{ $type->label() }}
                        </option>
                    @endforeach
                </x-forms.select>
            </div>

            <!-- Category Filter with Icon Group & Placeholder -->
            <div>
                <x-forms.select
                    name="cash_category_id"
                    placeholder="-- Semua Kategori --"
                    :selected="request('cash_category_id')"
                >
                    <x-slot:iconSlot>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </x-slot:iconSlot>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('cash_category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->type->label() }})
                        </option>
                    @endforeach
                </x-forms.select>
            </div>

            <!-- Start Date with Icon Group & Placeholder -->
            <div>
                <x-forms.input
                    type="date"
                    name="start_date"
                    placeholder="Dari Tanggal"
                    value="{{ request('start_date') }}"
                >
                    <x-slot:iconSlot>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </x-slot:iconSlot>
                </x-forms.input>
            </div>

            <!-- End Date & Submit Action -->
            <div class="flex items-center gap-2">
                <x-forms.input
                    type="date"
                    name="end_date"
                    placeholder="Sampai Tanggal"
                    value="{{ request('end_date') }}"
                >
                    <x-slot:iconSlot>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </x-slot:iconSlot>
                </x-forms.input>

                <x-buttons.primary type="submit" title="Terapkan Filter">
                    <x-slot:icon>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </x-slot:icon>
                    Cari
                </x-buttons.primary>

                @if (request()->hasAny(['search', 'type', 'cash_category_id', 'start_date', 'end_date']))
                    <x-buttons.secondary :href="route('modules.cash.transactions.index')" title="Reset Filter">
                        <x-slot:icon>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </x-slot:icon>
                        Reset
                    </x-buttons.secondary>
                @endif
            </div>
        </form>
    </div>

    <!-- Transactions Data Table -->
    @if ($transactions->isEmpty())
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xs">
            <x-tables.empty-state
                title="Tidak Ada Transaksi Kas"
                message="Belum ada catatan transaksi kas yang sesuai dengan kriteria filter Anda."
                :actionUrl="route('modules.cash.transactions.create')"
                actionText="Catat Transaksi Pertama"
            />
        </div>
    @else
        <x-tables.table :hasNumbering="true" numberingHeader="#">
            <x-slot:thead>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
                    No. Transaksi
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
                    Tanggal
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 text-center">
                    Tipe
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
                    Kategori & Keterangan
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 text-right">
                    Nominal (Rp)
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
                    Dibuat Oleh
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 text-right">
                    Aksi
                </th>
            </x-slot:thead>

            @foreach ($transactions as $trx)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                    <!-- Penomoran Otomatis # -->
                    <td class="px-5 py-4 text-xs font-medium text-gray-500 dark:text-gray-400 text-center">
                        {{ $transactions->firstItem() + $loop->index }}
                    </td>

                    <!-- No. Transaksi -->
                    <td class="px-5 py-4">
                        <a href="{{ route('modules.cash.transactions.show', $trx) }}" class="text-sm font-mono font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:underline">
                            {{ $trx->transaction_number }}
                        </a>
                        @if ($trx->reference)
                            <div class="text-xs text-gray-400 font-mono">Ref: {{ $trx->reference }}</div>
                        @endif
                    </td>

                    <!-- Tanggal -->
                    <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">
                        {{ $trx->transaction_date->format('d/m/Y') }}
                    </td>

                    <!-- Tipe Badge -->
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $trx->type->badgeClass() }}">
                            {{ $trx->type->label() }}
                        </span>
                    </td>

                    <!-- Kategori & Keterangan -->
                    <td class="px-5 py-4 max-w-xs">
                        <div class="text-sm font-semibold text-gray-800 dark:text-white/90">
                            {{ $trx->category ? $trx->category->name : 'Kategori Dihapus' }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1">
                            {{ $trx->description }}
                        </div>
                    </td>

                    <!-- Nominal (Green for Income, Red for Expense) -->
                    <td class="px-5 py-4 text-right whitespace-nowrap">
                        <span class="text-sm font-bold {{ $trx->type->value === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $trx->type->value === 'income' ? '+' : '-' }} {{ $trx->formatted_amount }}
                        </span>
                    </td>

                    <!-- Dibuat Oleh -->
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <img src="{{ $trx->creator ? $trx->creator->avatar_url : '' }}" class="w-6 h-6 rounded-full object-cover border border-gray-200 dark:border-gray-700" alt="Avatar" />
                            <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                {{ $trx->creator ? $trx->creator->name : 'Sistem' }}
                            </span>
                        </div>
                    </td>

                    <!-- Aksi (KHUSUS ICON ONLY DENGAN TOOLTIP) -->
                    <td class="px-5 py-4 text-right">
                        <div class="inline-flex items-center justify-end gap-1">
                            <!-- Detail Button: Icon Only -->
                            <x-buttons.action-icon
                                variant="view"
                                title="Lihat Detail Transaksi {{ $trx->transaction_number }}"
                                :href="route('modules.cash.transactions.show', $trx)"
                            />

                            <!-- Edit Button: Icon Only -->
                            <x-buttons.action-icon
                                variant="edit"
                                title="Edit Transaksi {{ $trx->transaction_number }}"
                                :href="route('modules.cash.transactions.edit', $trx)"
                            />

                            <!-- Delete Button: Icon Only -->
                            <x-buttons.action-icon
                                variant="delete"
                                title="Hapus Transaksi {{ $trx->transaction_number }}"
                                @click="$dispatch('open-confirm-modal', {
                                    action: '{{ route('modules.cash.transactions.destroy', $trx) }}',
                                    message: 'Apakah Anda yakin ingin menghapus transaksi {{ $trx->transaction_number }} sebesar {{ $trx->formatted_amount }}?'
                                })"
                            />
                        </div>
                    </td>
                </tr>
            @endforeach

            <x-slot:pagination>
                {{ $transactions->links() }}
            </x-slot:pagination>
        </x-tables.table>
    @endif
</div>

<!-- Modal Konfirmasi Hapus -->
<x-modals.confirm />
@endsection
