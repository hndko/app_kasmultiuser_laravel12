@extends('layouts.app-modules')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Kategori Kas
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Kelola kategori untuk klasifikasi transaksi kas masuk dan keluar.
            </p>
        </div>
        <div>
            <!-- Button with Icon Group + Text -->
            <x-buttons.primary :href="route('modules.cash.categories.create')">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </x-slot:icon>
                Tambah Kategori
            </x-buttons.primary>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs">
        <form method="GET" action="{{ route('modules.cash.categories.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Keyword with Icon Group & Placeholder -->
            <div>
                <x-forms.input
                    name="search"
                    placeholder="Cari nama / kode kategori..."
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </x-slot:iconSlot>
                    @foreach ($types as $type)
                        <option value="{{ $type->value }}" {{ request('type') === $type->value ? 'selected' : '' }}>
                            {{ $type->label() }}
                        </option>
                    @endforeach
                </x-forms.select>
            </div>

            <!-- Status Filter with Icon Group & Placeholder -->
            <div>
                <x-forms.select
                    name="status"
                    placeholder="-- Semua Status --"
                    :selected="request('status')"
                >
                    <x-slot:iconSlot>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </x-slot:iconSlot>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </x-forms.select>
            </div>

            <!-- Filter Buttons with Icon Group + Text -->
            <div class="flex items-center gap-2">
                <x-buttons.primary type="submit" class="w-full">
                    <x-slot:icon>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </x-slot:icon>
                    Filter
                </x-buttons.primary>

                @if (request()->hasAny(['search', 'type', 'status']))
                    <x-buttons.secondary :href="route('modules.cash.categories.index')" title="Reset Filter">
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

    <!-- Data Table -->
    @if ($categories->isEmpty())
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xs">
            <x-tables.empty-state
                title="Tidak Ada Kategori Ditemukan"
                message="Kategori kas belum tersedia atau tidak sesuai filter pencarian Anda."
                :actionUrl="route('modules.cash.categories.create')"
                actionText="Tambah Kategori Pertama"
            />
        </div>
    @else
        <x-tables.table :hasNumbering="true" numberingHeader="#">
            <x-slot:thead>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
                    Kode
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
                    Nama Kategori
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 text-center">
                    Tipe
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 text-center">
                    Status
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 text-center">
                    Jml Transaksi
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 text-right">
                    Aksi
                </th>
            </x-slot:thead>

            @foreach ($categories as $category)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                    <!-- Penomoran Otomatis # -->
                    <td class="px-5 py-4 text-xs font-medium text-gray-500 dark:text-gray-400 text-center">
                        {{ $categories->firstItem() + $loop->index }}
                    </td>

                    <!-- Kode -->
                    <td class="px-5 py-4 text-sm font-mono font-medium text-brand-600 dark:text-brand-400">
                        {{ $category->code }}
                    </td>

                    <!-- Nama & Deskripsi -->
                    <td class="px-5 py-4">
                        <div class="text-sm font-semibold text-gray-800 dark:text-white/90">
                            {{ $category->name }}
                        </div>
                        @if ($category->description)
                            <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1">
                                {{ $category->description }}
                            </div>
                        @endif
                    </td>

                    <!-- Tipe Badge -->
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->type->badgeClass() }}">
                            {{ $category->type->label() }}
                        </span>
                    </td>

                    <!-- Status Badge -->
                    <td class="px-5 py-4 text-center">
                        @if ($category->is_active)
                            <x-badges.badge variant="success">Aktif</x-badges.badge>
                        @else
                            <x-badges.badge variant="gray">Nonaktif</x-badges.badge>
                        @endif
                    </td>

                    <!-- Jml Transaksi -->
                    <td class="px-5 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-800 text-xs font-semibold">
                            {{ $category->transactions_count }}
                        </span>
                    </td>

                    <!-- Aksi (KHUSUS ICON ONLY) -->
                    <td class="px-5 py-4 text-right">
                        <div class="inline-flex items-center justify-end gap-1">
                            <!-- Edit Button: Icon Only -->
                            <x-buttons.action-icon
                                variant="edit"
                                title="Edit Kategori {{ $category->name }}"
                                :href="route('modules.cash.categories.edit', $category)"
                            />

                            <!-- Delete Button: Icon Only -->
                            <x-buttons.action-icon
                                variant="delete"
                                title="Hapus Kategori {{ $category->name }}"
                                @click="$dispatch('open-confirm-modal', {
                                    action: '{{ route('modules.cash.categories.destroy', $category) }}',
                                    message: 'Apakah Anda yakin ingin menghapus kategori {{ addslashes($category->name) }}?'
                                })"
                            />
                        </div>
                    </td>
                </tr>
            @endforeach

            <x-slot:pagination>
                {{ $categories->links() }}
            </x-slot:pagination>
        </x-tables.table>
    @endif
</div>

<!-- Modal Konfirmasi Hapus -->
<x-modals.confirm />
@endsection
