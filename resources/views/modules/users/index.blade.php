@extends('layouts.app-modules')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Manajemen Pengguna
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Kelola hak akses pengguna, peran administrator/user, dan status akun.
            </p>
        </div>
        <div>
            <!-- Button with Icon Group + Text -->
            <x-buttons.primary :href="route('modules.users.create')">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </x-slot:icon>
                Tambah Pengguna Baru
            </x-buttons.primary>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-4 sm:p-5 shadow-xs">
        <form method="GET" action="{{ route('modules.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Search Keyword with Icon Group & Placeholder -->
            <div>
                <x-forms.input
                    name="search"
                    placeholder="Cari nama atau email..."
                    value="{{ request('search') }}"
                >
                    <x-slot:iconSlot>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </x-slot:iconSlot>
                </x-forms.input>
            </div>

            <!-- Role Filter with Icon Group & Placeholder -->
            <div>
                <x-forms.select
                    name="role"
                    placeholder="-- Semua Role --"
                    :selected="request('role')"
                >
                    <x-slot:iconSlot>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </x-slot:iconSlot>
                    @foreach ($roles as $role)
                        <option value="{{ $role->value }}" {{ request('role') === $role->value ? 'selected' : '' }}>
                            {{ $role->label() }}
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
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
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

                @if (request()->hasAny(['search', 'role', 'status']))
                    <x-buttons.secondary :href="route('modules.users.index')" title="Reset Filter">
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
    @if ($users->isEmpty())
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xs">
            <x-tables.empty-state
                title="Tidak Ada Pengguna"
                message="Data pengguna tidak ditemukan sesuai dengan filter pencarian."
                :actionUrl="route('modules.users.create')"
                actionText="Tambah Pengguna Baru"
            />
        </div>
    @else
        <x-tables.table :hasNumbering="true" numberingHeader="#">
            <x-slot:thead>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
                    Pengguna
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 text-center">
                    Role
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 text-center">
                    Status
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 text-center">
                    Jml Transaksi
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
                    Terakhir Masuk
                </th>
                <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 text-right">
                    Aksi
                </th>
            </x-slot:thead>

            @foreach ($users as $userItem)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                    <!-- Penomoran Otomatis # -->
                    <td class="px-5 py-4 text-xs font-medium text-gray-500 dark:text-gray-400 text-center">
                        {{ $users->firstItem() + $loop->index }}
                    </td>

                    <!-- Info Pengguna: Avatar, Nama, Email -->
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $userItem->avatar_url }}" alt="{{ $userItem->name }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-gray-700 shrink-0" />
                            <div>
                                <div class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                    {{ $userItem->name }}
                                    @if ($userItem->id === auth()->id())
                                        <span class="ml-1 text-xs text-brand-600 dark:text-brand-400 font-medium">(Anda)</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $userItem->email }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Role Badge -->
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $userItem->role->badgeClass() }}">
                            {{ $userItem->role->label() }}
                        </span>
                    </td>

                    <!-- Status Badge -->
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $userItem->status->badgeClass() }}">
                            {{ $userItem->status->label() }}
                        </span>
                    </td>

                    <!-- Jml Transaksi Dibuat -->
                    <td class="px-5 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-800 text-xs font-semibold">
                            {{ $userItem->created_transactions_count }}
                        </span>
                    </td>

                    <!-- Terakhir Masuk -->
                    <td class="px-5 py-4 text-xs text-gray-500 dark:text-gray-400">
                        {{ $userItem->last_login_at ? $userItem->last_login_at->diffForHumans() : 'Belum pernah' }}
                    </td>

                    <!-- Aksi (KHUSUS ICON ONLY) -->
                    <td class="px-5 py-4 text-right">
                        <div class="inline-flex items-center justify-end gap-1">
                            <!-- Edit Button: Icon Only -->
                            <x-buttons.action-icon
                                variant="edit"
                                title="Edit Pengguna {{ $userItem->name }}"
                                :href="route('modules.users.edit', $userItem)"
                            />

                            @if ($userItem->id !== auth()->id())
                                <!-- Delete Button: Icon Only -->
                                <x-buttons.action-icon
                                    variant="delete"
                                    title="Hapus Pengguna {{ $userItem->name }}"
                                    @click="$dispatch('open-confirm-modal', {
                                        action: '{{ route('modules.users.destroy', $userItem) }}',
                                        message: 'Apakah Anda yakin ingin menghapus pengguna {{ addslashes($userItem->name) }} ({{ $userItem->email }})?'
                                    })"
                                />
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach

            <x-slot:pagination>
                {{ $users->links() }}
            </x-slot:pagination>
        </x-tables.table>
    @endif
</div>

<!-- Modal Konfirmasi Hapus -->
<x-modals.confirm />
@endsection
