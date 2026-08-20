@extends('layouts.app-modules')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Laporan Arus Kas
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Laporan mutasi kas masuk, kas keluar, dan saldo kas per periode.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Print / Export Button with Icon Group + Text -->
            <x-buttons.primary :href="route('modules.reports.cash.print', request()->query())" target="_blank">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                </x-slot:icon>
                Cetak Laporan (Print / PDF)
            </x-buttons.primary>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-xs space-y-4">
        <form method="GET" action="{{ route('modules.reports.cash.index') }}" class="space-y-4">
            
            <!-- Quick Preset Period Tabs -->
            <div class="flex flex-wrap items-center gap-2 border-b border-gray-100 dark:border-gray-800 pb-3">
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 mr-1">Pilihan Cepat:</span>
                
                @php
                    $activePeriod = request('period', 'this_month');
                @endphp

                <a href="{{ route('modules.reports.cash.index', array_merge(request()->except(['start_date', 'end_date']), ['period' => 'today'])) }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $activePeriod === 'today' ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300' }}">
                    Hari Ini
                </a>

                <a href="{{ route('modules.reports.cash.index', array_merge(request()->except(['start_date', 'end_date']), ['period' => 'this_month'])) }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $activePeriod === 'this_month' ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300' }}">
                    Bulan Ini
                </a>

                <a href="{{ route('modules.reports.cash.index', array_merge(request()->except(['start_date', 'end_date']), ['period' => 'last_month'])) }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $activePeriod === 'last_month' ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300' }}">
                    Bulan Lalu
                </a>

                <a href="{{ route('modules.reports.cash.index', array_merge(request()->except(['start_date', 'end_date']), ['period' => 'this_year'])) }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-medium transition {{ $activePeriod === 'this_year' ? 'bg-brand-500 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300' }}">
                    Tahun Ini
                </a>
            </div>

            <!-- Custom Filter Grid -->
            <input type="hidden" name="period" value="custom" />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Start Date Input with Icon Group & Placeholder -->
                <div>
                    <x-forms.input
                        type="date"
                        name="start_date"
                        label="Dari Tanggal"
                        placeholder="YYYY-MM-DD"
                        value="{{ $report['start_date'] }}"
                    >
                        <x-slot:iconSlot>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </x-slot:iconSlot>
                    </x-forms.input>
                </div>

                <!-- End Date Input with Icon Group & Placeholder -->
                <div>
                    <x-forms.input
                        type="date"
                        name="end_date"
                        label="Sampai Tanggal"
                        placeholder="YYYY-MM-DD"
                        value="{{ $report['end_date'] }}"
                    >
                        <x-slot:iconSlot>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </x-slot:iconSlot>
                    </x-forms.input>
                </div>

                <!-- Type Filter with Icon Group & Placeholder -->
                <div>
                    <x-forms.select
                        name="type"
                        label="Tipe Transaksi"
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
                        label="Kategori Kas"
                        placeholder="-- Semua Kategori --"
                        :selected="request('cash_category_id')"
                    >
                        <x-slot:iconSlot>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </x-slot:iconSlot>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('cash_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </x-forms.select>
                </div>

                <!-- Filter Actions -->
                <div class="flex items-end gap-2">
                    <x-buttons.primary type="submit" class="w-full">
                        <x-slot:icon>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                        </x-slot:icon>
                        Tampilkan Laporan
                    </x-buttons.primary>
                </div>
            </div>
        </form>
    </div>

    <!-- Financial Summary Highlights -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Saldo Awal -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-xs">
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Saldo Awal</span>
            <h4 class="text-xl font-bold text-gray-800 dark:text-white/90 mt-1">
                {{ $report['formatted_initial_balance'] }}
            </h4>
            <span class="text-xs text-gray-400">Sebelum {{ $report['formatted_start_date'] }}</span>
        </div>

        <!-- Total Pemasukan -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-xs">
            <span class="text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wider">Total Pemasukan</span>
            <h4 class="text-xl font-bold text-green-600 dark:text-green-400 mt-1">
                {{ $report['formatted_total_income'] }}
            </h4>
            <span class="text-xs text-gray-400">Periode terpilih</span>
        </div>

        <!-- Total Pengeluaran -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-xs">
            <span class="text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wider">Total Pengeluaran</span>
            <h4 class="text-xl font-bold text-red-600 dark:text-red-400 mt-1">
                {{ $report['formatted_total_expense'] }}
            </h4>
            <span class="text-xs text-gray-400">Periode terpilih</span>
        </div>

        <!-- Saldo Akhir -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-xs">
            <span class="text-xs font-medium text-brand-600 dark:text-brand-400 uppercase tracking-wider">Saldo Akhir</span>
            <h4 class="text-xl font-bold {{ $report['ending_balance'] >= 0 ? 'text-gray-900 dark:text-white' : 'text-red-600 dark:text-red-400' }} mt-1">
                {{ $report['formatted_ending_balance'] }}
            </h4>
            <span class="text-xs text-gray-400">Per {{ $report['formatted_end_date'] }}</span>
        </div>
    </div>

    <!-- Ledger Table -->
    @if (empty($report['transactions']))
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xs">
            <x-tables.empty-state
                title="Tidak Ada Mutasi Kas"
                message="Tidak terdapat catatan transaksi kas pada rentang tanggal yang dipilih."
            />
        </div>
    @else
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xs overflow-hidden">
            <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                    Buku Besar Mutasi Kas ({{ $report['transaction_count'] }} Transaksi)
                </h3>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    Periode: {{ $report['formatted_start_date'] }} s/d {{ $report['formatted_end_date'] }}
                </span>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50/75 dark:bg-white/[0.02] text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400">
                            <!-- Penomoran otomatis # -->
                            <th class="px-4 py-3 text-center w-12">#</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">No. Trx / Ref</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Keterangan</th>
                            <th class="px-4 py-3 text-right text-green-600 dark:text-green-400">Kas Masuk (Rp)</th>
                            <th class="px-4 py-3 text-right text-red-600 dark:text-red-400">Kas Keluar (Rp)</th>
                            <th class="px-4 py-3 text-right text-brand-600 dark:text-brand-400">Saldo (Rp)</th>
                            <th class="px-4 py-3">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <!-- Initial Balance Row -->
                        <tr class="bg-gray-50/40 dark:bg-white/[0.01] font-semibold text-xs text-gray-600 dark:text-gray-400">
                            <td class="px-4 py-2.5 text-center">-</td>
                            <td class="px-4 py-2.5">{{ $report['start_date'] }}</td>
                            <td class="px-4 py-2.5 font-mono italic">-</td>
                            <td class="px-4 py-2.5 italic">Saldo Awal</td>
                            <td class="px-4 py-2.5 italic text-gray-400">Saldo awal sebelum periode laporan</td>
                            <td class="px-4 py-2.5 text-right">-</td>
                            <td class="px-4 py-2.5 text-right">-</td>
                            <td class="px-4 py-2.5 text-right font-mono font-bold">{{ $report['formatted_initial_balance'] }}</td>
                            <td class="px-4 py-2.5">-</td>
                        </tr>

                        <!-- Transaction Ledger Rows -->
                        @foreach ($report['transactions'] as $trx)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                                <!-- Penomoran Otomatis # -->
                                <td class="px-4 py-3.5 text-center text-xs text-gray-400">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-4 py-3.5 text-xs text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                    {{ $trx->transaction_date->format('d/m/Y') }}
                                </td>

                                <td class="px-4 py-3.5">
                                    <span class="font-mono text-xs font-semibold text-brand-600 dark:text-brand-400">{{ $trx->transaction_number }}</span>
                                    @if ($trx->reference)
                                        <div class="text-[11px] text-gray-400 font-mono">Ref: {{ $trx->reference }}</div>
                                    @endif
                                </td>

                                <td class="px-4 py-3.5 text-xs text-gray-800 dark:text-white/90">
                                    {{ $trx->category ? $trx->category->name : '-' }}
                                </td>

                                <td class="px-4 py-3.5 text-xs text-gray-600 dark:text-gray-400 max-w-xs">
                                    {{ $trx->description }}
                                </td>

                                <!-- Kas Masuk -->
                                <td class="px-4 py-3.5 text-right text-xs font-semibold text-green-600 dark:text-green-400 whitespace-nowrap font-mono">
                                    {{ $trx->type->value === 'income' ? number_format($trx->amount, 0, ',', '.') : '-' }}
                                </td>

                                <!-- Kas Keluar -->
                                <td class="px-4 py-3.5 text-right text-xs font-semibold text-red-600 dark:text-red-400 whitespace-nowrap font-mono">
                                    {{ $trx->type->value === 'expense' ? number_format($trx->amount, 0, ',', '.') : '-' }}
                                </td>

                                <!-- Saldo Berjalan -->
                                <td class="px-4 py-3.5 text-right text-xs font-bold text-gray-900 dark:text-white whitespace-nowrap font-mono">
                                    {{ $trx->formatted_running_balance }}
                                </td>

                                <td class="px-4 py-3.5 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $trx->creator ? $trx->creator->name : 'Sistem' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <!-- Ending Summary Footer Row -->
                        <tr class="bg-gray-100/75 dark:bg-white/[0.04] font-bold text-xs border-t-2 border-gray-200 dark:border-gray-700">
                            <td colspan="5" class="px-4 py-3.5 text-right uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Total Pergerakan & Saldo Akhir :
                            </td>
                            <td class="px-4 py-3.5 text-right text-green-600 dark:text-green-400 font-mono">
                                {{ $report['formatted_total_income'] }}
                            </td>
                            <td class="px-4 py-3.5 text-right text-red-600 dark:text-red-400 font-mono">
                                {{ $report['formatted_total_expense'] }}
                            </td>
                            <td class="px-4 py-3.5 text-right text-brand-600 dark:text-brand-400 font-mono font-extrabold text-sm">
                                {{ $report['formatted_ending_balance'] }}
                            </td>
                            <td class="px-4 py-3.5"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
