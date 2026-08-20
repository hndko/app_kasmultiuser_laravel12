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
                Detail Transaksi: {{ $transaction->transaction_number }}
            </h1>
        </div>

        <div class="flex items-center gap-2">
            <!-- Edit Button: Icon + Text -->
            <x-buttons.secondary :href="route('modules.cash.transactions.edit', $transaction)">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </x-slot:icon>
                Edit
            </x-buttons.secondary>

            <!-- Delete Button: Icon + Text -->
            <x-buttons.danger
                type="button"
                @click="$dispatch('open-confirm-modal', {
                    action: '{{ route('modules.cash.transactions.destroy', $transaction) }}',
                    message: 'Apakah Anda yakin ingin menghapus transaksi {{ $transaction->transaction_number }} sebesar {{ $transaction->formatted_amount }}?'
                })"
            >
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </x-slot:icon>
                Hapus
            </x-buttons.danger>
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 sm:p-8 shadow-xs space-y-6">
        
        <!-- Top Status & Amount Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 rounded-xl {{ $transaction->type->value === 'income' ? 'bg-green-50/50 border border-green-200/50 dark:bg-green-500/10 dark:border-green-500/20' : 'bg-red-50/50 border border-red-200/50 dark:bg-red-500/10 dark:border-red-500/20' }}">
            <div>
                <span class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                    Nominal Transaksi ({{ $transaction->type->label() }})
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold {{ $transaction->type->value === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} mt-1">
                    {{ $transaction->formatted_amount }}
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $transaction->type->badgeClass() }}">
                    {{ $transaction->type->label() }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                    {{ $transaction->category ? $transaction->category->name : 'Tanpa Kategori' }}
                </span>
            </div>
        </div>

        <!-- Detail Attributes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-gray-100 dark:border-gray-800 pb-6">
            <div>
                <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider">No. Transaksi</span>
                <p class="text-sm font-mono font-semibold text-gray-800 dark:text-white/90 mt-1">
                    {{ $transaction->transaction_number }}
                </p>
            </div>

            <div>
                <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider">Tanggal Transaksi</span>
                <p class="text-sm font-semibold text-gray-800 dark:text-white/90 mt-1">
                    {{ $transaction->transaction_date->translatedFormat('l, d F Y') }}
                </p>
            </div>

            <div>
                <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider">Kategori Kas</span>
                <p class="text-sm font-semibold text-gray-800 dark:text-white/90 mt-1">
                    {{ $transaction->category ? $transaction->category->name : '-' }}
                    @if ($transaction->category)
                        <span class="text-xs text-gray-400 font-mono">({{ $transaction->category->code }})</span>
                    @endif
                </p>
            </div>

            <div>
                <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider">No. Referensi / Bukti</span>
                <p class="text-sm font-mono text-gray-800 dark:text-white/90 mt-1">
                    {{ $transaction->reference ?: '-' }}
                </p>
            </div>
        </div>

        <!-- Description Section -->
        <div class="border-b border-gray-100 dark:border-gray-800 pb-6">
            <span class="text-xs text-gray-400 dark:text-gray-500 uppercase tracking-wider">Keterangan / Rincian</span>
            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line mt-2 leading-relaxed bg-gray-50 dark:bg-white/[0.02] p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                {{ $transaction->description }}
            </p>
        </div>

        <!-- Audit Trail Log -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
            <!-- Created By -->
            <div class="flex items-start gap-3">
                <img src="{{ $transaction->creator ? $transaction->creator->avatar_url : '' }}" alt="Creator" class="w-9 h-9 rounded-full object-cover border border-gray-200 dark:border-gray-700" />
                <div>
                    <span class="text-xs text-gray-400 dark:text-gray-500">Dicatat oleh</span>
                    <h5 class="text-xs font-semibold text-gray-800 dark:text-white/90">
                        {{ $transaction->creator ? $transaction->creator->name : 'Sistem' }}
                    </h5>
                    <span class="text-xs text-gray-400">
                        {{ $transaction->created_at->translatedFormat('d M Y, H:i') }} WIB
                    </span>
                </div>
            </div>

            <!-- Updated By -->
            @if ($transaction->updater)
                <div class="flex items-start gap-3">
                    <img src="{{ $transaction->updater->avatar_url }}" alt="Updater" class="w-9 h-9 rounded-full object-cover border border-gray-200 dark:border-gray-700" />
                    <div>
                        <span class="text-xs text-gray-400 dark:text-gray-500">Terakhir diedit oleh</span>
                        <h5 class="text-xs font-semibold text-gray-800 dark:text-white/90">
                            {{ $transaction->updater->name }}
                        </h5>
                        <span class="text-xs text-gray-400">
                            {{ $transaction->updated_at->translatedFormat('d M Y, H:i') }} WIB
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<x-modals.confirm />
@endsection
