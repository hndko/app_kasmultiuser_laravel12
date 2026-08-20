<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    
    <!-- Inline print-optimized styling -->
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #111827;
        }

        body {
            background-color: #f9fafb;
            padding: 20px;
        }

        .print-container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 13px;
            color: #4b5563;
        }

        .meta-info {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 20px;
            background: #f3f4f6;
            padding: 10px 15px;
            border-radius: 6px;
        }

        .summary-box {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .summary-item {
            border: 1px solid #e5e7eb;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
        }

        .summary-item .label {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 600;
        }

        .summary-item .value {
            font-size: 14px;
            font-weight: bold;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 25px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f9fafb;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: bold; }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding: 0 40px;
        }

        .signature-col {
            text-align: center;
            font-size: 12px;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .no-print {
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-print {
            background-color: #465fff;
            color: white;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
        }

        @media print {
            body { background: white; padding: 0; }
            .print-container { box-shadow: none; padding: 0; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan sebagai PDF</button>
    </div>

    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <h1>{{ config('app.name', 'Sistem Kas Sederhana Multi-User') }}</h1>
            <p>Laporan Rekapitulasi Mutasi Buku Kas</p>
        </div>

        <!-- Meta -->
        <div class="meta-info">
            <div><strong>Periode:</strong> {{ $report['formatted_start_date'] }} s/d {{ $report['formatted_end_date'] }}</div>
            <div><strong>Dicetak pada:</strong> {{ now()->translatedFormat('d F Y, H:i') }} WIB oleh {{ auth()->user()->name }}</div>
        </div>

        <!-- Summary -->
        <div class="summary-box">
            <div class="summary-item">
                <div class="label">Saldo Awal</div>
                <div class="value font-mono">{{ $report['formatted_initial_balance'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Pemasukan</div>
                <div class="value font-mono" style="color: #16a34a;">{{ $report['formatted_total_income'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Pengeluaran</div>
                <div class="value font-mono" style="color: #dc2626;">{{ $report['formatted_total_expense'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Saldo Akhir</div>
                <div class="value font-mono" style="color: #465fff;">{{ $report['formatted_ending_balance'] }}</div>
            </div>
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">#</th>
                    <th style="width: 75px;">Tanggal</th>
                    <th style="width: 120px;">No. Trx / Ref</th>
                    <th>Kategori</th>
                    <th>Keterangan</th>
                    <th class="text-right" style="width: 100px;">Kas Masuk (Rp)</th>
                    <th class="text-right" style="width: 100px;">Kas Keluar (Rp)</th>
                    <th class="text-right" style="width: 110px;">Saldo (Rp)</th>
                    <th style="width: 80px;">Petugas</th>
                </tr>
            </thead>
            <tbody>
                <!-- Initial Balance -->
                <tr style="background: #f9fafb; font-style: italic;">
                    <td class="text-center">-</td>
                    <td>{{ $report['start_date'] }}</td>
                    <td class="font-mono">-</td>
                    <td>Saldo Awal</td>
                    <td>Saldo awal periode laporan</td>
                    <td class="text-right">-</td>
                    <td class="text-right">-</td>
                    <td class="text-right font-mono font-bold">{{ $report['formatted_initial_balance'] }}</td>
                    <td>-</td>
                </tr>

                @forelse ($report['transactions'] as $trx)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $trx->transaction_date->format('d/m/Y') }}</td>
                        <td class="font-mono">{{ $trx->transaction_number }}</td>
                        <td>{{ $trx->category ? $trx->category->name : '-' }}</td>
                        <td>{{ $trx->description }}</td>
                        <td class="text-right font-mono">{{ $trx->type->value === 'income' ? number_format($trx->amount, 0, ',', '.') : '-' }}</td>
                        <td class="text-right font-mono">{{ $trx->type->value === 'expense' ? number_format($trx->amount, 0, ',', '.') : '-' }}</td>
                        <td class="text-right font-mono font-bold">{{ $trx->formatted_running_balance }}</td>
                        <td>{{ $trx->creator ? $trx->creator->name : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 20px;">Tidak ada data mutasi kas pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background: #f3f4f6; font-weight: bold;">
                    <td colspan="5" class="text-right">TOTAL & SALDO AKHIR :</td>
                    <td class="text-right font-mono">{{ $report['formatted_total_income'] }}</td>
                    <td class="text-right font-mono">{{ $report['formatted_total_expense'] }}</td>
                    <td class="text-right font-mono font-bold">{{ $report['formatted_ending_balance'] }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-col">
                <p>Dibuat Oleh,</p>
                <div class="signature-space"></div>
                <p class="signature-name">{{ auth()->user()->name }}</p>
                <p style="font-size: 10px; color: #6b7280;">{{ auth()->user()->role->label() }}</p>
            </div>

            <div class="signature-col">
                <p>Mengetahui / Menyetujui,</p>
                <div class="signature-space"></div>
                <p class="signature-name">( ........................................ )</p>
                <p style="font-size: 10px; color: #6b7280;">Pimpinan / Manajer Keuangan</p>
            </div>
        </div>
    </div>

</body>
</html>
