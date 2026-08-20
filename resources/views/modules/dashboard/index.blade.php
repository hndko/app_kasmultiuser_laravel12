@extends('layouts.app-modules')

@section('content')
<div class="space-y-6">
    <!-- Top Welcome & Quick Actions Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-xs">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Halo, {{ auth()->user()->name }}! 👋
                </h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ auth()->user()->role->badgeClass() }}">
                    {{ auth()->user()->role->label() }}
                </span>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Berikut adalah ringkasan performa dan arus kas per {{ now()->translatedFormat('d F Y') }}.
            </p>
        </div>

        <!-- Quick Action Buttons with Icon Group + Text -->
        <div class="flex flex-wrap items-center gap-3">
            <x-buttons.primary :href="route('modules.cash.transactions.create', ['type' => 'income'])" class="!bg-green-600 hover:!bg-green-700">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </x-slot:icon>
                Kas Masuk
            </x-buttons.primary>

            <x-buttons.danger :href="route('modules.cash.transactions.create', ['type' => 'expense'])">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                </x-slot:icon>
                Kas Keluar
            </x-buttons.danger>

            <x-buttons.secondary :href="route('modules.reports.cash.index')">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </x-slot:icon>
                Laporan Kas
            </x-buttons.secondary>
        </div>
    </div>

    <!-- 4 Primary Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Saldo Kas Total -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Saldo Kas</span>
                <div class="w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-brand-600 dark:text-brand-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-xl sm:text-2xl font-bold {{ $metrics['total_balance'] >= 0 ? 'text-gray-900 dark:text-white' : 'text-red-600 dark:text-red-400' }}">
                {{ $metrics['formatted_total_balance'] }}
            </h3>
            <p class="text-xs text-gray-400 mt-1">Akumulasi seluruh transaksi</p>
        </div>

        <!-- Pemasukan Bulan Ini -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Pemasukan Bulan Ini</span>
                <div class="w-10 h-10 rounded-xl bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                </div>
            </div>
            <h3 class="text-xl sm:text-2xl font-bold text-green-600 dark:text-green-400">
                {{ $metrics['formatted_month_income'] }}
            </h3>
            <p class="text-xs text-gray-400 mt-1">Periode {{ now()->translatedFormat('F Y') }}</p>
        </div>

        <!-- Pengeluaran Bulan Ini -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Pengeluaran Bulan Ini</span>
                <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                    </svg>
                </div>
            </div>
            <h3 class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-400">
                {{ $metrics['formatted_month_expense'] }}
            </h3>
            <p class="text-xs text-gray-400 mt-1">Periode {{ now()->translatedFormat('F Y') }}</p>
        </div>

        <!-- Selisih / Net Bulan Ini -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Surplus / Defisit Bulan Ini</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <h3 class="text-xl sm:text-2xl font-bold {{ $metrics['month_net'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                {{ $metrics['formatted_month_net'] }}
            </h3>
            <p class="text-xs text-gray-400 mt-1">{{ $metrics['month_trx_count'] }} Transaksi bulan ini</p>
        </div>
    </div>

    <!-- Charts & Category Breakdown Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Monthly Cashflow Trend -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        Tren Arus Kas (6 Bulan Terakhir)
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Perbandingan Kas Masuk vs Kas Keluar
                    </p>
                </div>
                <div class="flex items-center gap-4 text-xs font-medium">
                    <span class="flex items-center gap-1.5 text-green-600 dark:text-green-400">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span> Kas Masuk
                    </span>
                    <span class="flex items-center gap-1.5 text-red-600 dark:text-red-400">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span> Kas Keluar
                    </span>
                </div>
            </div>

            <!-- Visual Bar Chart Summary -->
            <div class="space-y-4 pt-2">
                @foreach ($cashflowTrend['labels'] as $idx => $label)
                    @php
                        $inc = $cashflowTrend['income'][$idx];
                        $exp = $cashflowTrend['expense'][$idx];
                        $maxVal = max(max($cashflowTrend['income']), max($cashflowTrend['expense']), 1);
                        $incPct = min(round(($inc / $maxVal) * 100), 100);
                        $expPct = min(round(($exp / $maxVal) * 100), 100);
                    @endphp
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold text-gray-700 dark:text-gray-300 w-20">{{ $label }}</span>
                            <div class="flex items-center gap-3 text-xs">
                                <span class="text-green-600 font-medium">+Rp {{ number_format($inc, 0, ',', '.') }}</span>
                                <span class="text-gray-300 dark:text-gray-700">|</span>
                                <span class="text-red-600 font-medium">-Rp {{ number_format($exp, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Income Bar -->
                            <div class="h-2.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden flex justify-end">
                                <div class="h-full bg-green-500 rounded-full transition-all duration-500" style="width: {{ $incPct }}%"></div>
                            </div>
                            <!-- Expense Bar -->
                            <div class="h-2.5 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full bg-red-500 rounded-full transition-all duration-500" style="width: {{ $expPct }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Expense Categories Breakdown -->
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 shadow-xs space-y-4">
            <div class="border-b border-gray-100 dark:border-gray-800 pb-3">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                    Pengeluaran per Kategori
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Bulan {{ now()->translatedFormat('F Y') }}
                </p>
            </div>

            @if (empty($topExpenses))
                <div class="py-8 text-center text-xs text-gray-400">
                    Belum ada data pengeluaran bulan ini.
                </div>
            @else
                <div class="space-y-4 pt-1">
                    @php
                        $totalTopExp = array_sum(array_column($topExpenses, 'amount')) ?: 1;
                    @endphp
                    @foreach ($topExpenses as $expCat)
                        @php
                            $catPct = round(($expCat['amount'] / $totalTopExp) * 100, 1);
                        @endphp
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-medium text-gray-800 dark:text-white/90 truncate">{{ $expCat['name'] }}</span>
                                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $expCat['formatted_amount'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-brand-500 rounded-full" style="width: {{ $catPct }}%"></div>
                                </div>
                                <span class="text-[10px] font-mono text-gray-400 w-8 text-right">{{ $catPct }}%</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    Transaksi Terbaru
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    5 transaksi terakhir yang dicatat dalam sistem kas.
                </p>
            </div>
            <!-- View All Button with Icon Group + Text -->
            <x-buttons.secondary :href="route('modules.cash.transactions.index')">
                <x-slot:icon>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </x-slot:icon>
                Lihat Semua Transaksi
            </x-buttons.secondary>
        </div>

        @if ($recentTransactions->isEmpty())
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl shadow-xs">
                <x-tables.empty-state
                    title="Belum Ada Transaksi"
                    message="Mulai catat transaksi pertama Anda sekarang."
                    :actionUrl="route('modules.cash.transactions.create')"
                    actionText="Catat Transaksi"
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
                        Nominal
                    </th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 text-right">
                        Aksi
                    </th>
                </x-slot:thead>

                @foreach ($recentTransactions as $trx)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                        <!-- Auto-numbering # -->
                        <td class="px-5 py-4 text-xs font-medium text-gray-500 dark:text-gray-400 text-center">
                            {{ $loop->iteration }}
                        </td>

                        <!-- No. Transaksi -->
                        <td class="px-5 py-4 font-mono text-sm font-medium text-brand-600 dark:text-brand-400">
                            <a href="{{ route('modules.cash.transactions.show', $trx) }}" class="hover:underline">
                                {{ $trx->transaction_number }}
                            </a>
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
                                {{ $trx->category ? $trx->category->name : '-' }}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1">
                                {{ $trx->description }}
                            </div>
                        </td>

                        <!-- Nominal -->
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <span class="text-sm font-bold {{ $trx->type->value === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $trx->type->value === 'income' ? '+' : '-' }} {{ $trx->formatted_amount }}
                            </span>
                        </td>

                        <!-- Aksi (KHUSUS ICON ONLY) -->
                        <td class="px-5 py-4 text-right">
                            <div class="inline-flex items-center justify-end gap-1">
                                <!-- Detail Button: Icon Only -->
                                <x-buttons.action-icon
                                    variant="view"
                                    title="Lihat Detail {{ $trx->transaction_number }}"
                                    :href="route('modules.cash.transactions.show', $trx)"
                                />

                                <!-- Edit Button: Icon Only -->
                                <x-buttons.action-icon
                                    variant="edit"
                                    title="Edit {{ $trx->transaction_number }}"
                                    :href="route('modules.cash.transactions.edit', $trx)"
                                />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-tables.table>
        @endif
    </div>
</div>
@endsection
