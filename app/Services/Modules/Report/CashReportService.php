<?php

namespace App\Services\Modules\Report;

use App\Enums\TransactionType;
use App\Models\CashTransaction;
use Illuminate\Support\Carbon;

class CashReportService
{
    /**
     * Generate complete cash flow ledger report.
     */
    public function generateCashReport(array $filters = []): array
    {
        // Resolve date ranges based on preset or custom
        $period = $filters['period'] ?? 'this_month';
        $dates = $this->resolveDateRange($period, $filters['start_date'] ?? null, $filters['end_date'] ?? null);

        $startDate = $dates['start_date'];
        $endDate = $dates['end_date'];

        // 1. Calculate Saldo Awal (Beginning Balance before startDate)
        $incomeBefore = (float) CashTransaction::where('type', TransactionType::INCOME)
            ->whereDate('transaction_date', '<', $startDate)
            ->sum('amount');

        $expenseBefore = (float) CashTransaction::where('type', TransactionType::EXPENSE)
            ->whereDate('transaction_date', '<', $startDate)
            ->sum('amount');

        $initialBalance = $incomeBefore - $expenseBefore;

        // 2. Query transactions in range
        $query = CashTransaction::with(['category', 'creator'])
            ->whereDate('transaction_date', '>=', $startDate)
            ->whereDate('transaction_date', '<=', $endDate)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc');

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['cash_category_id'])) {
            $query->where('cash_category_id', $filters['cash_category_id']);
        }

        $transactions = $query->get();

        // 3. Compute running balance and summary totals
        $totalIncome = 0;
        $totalExpense = 0;
        $currentRunningBalance = $initialBalance;

        $ledgerRows = [];
        foreach ($transactions as $trx) {
            if ($trx->type === TransactionType::INCOME) {
                $totalIncome += (float)$trx->amount;
                $currentRunningBalance += (float)$trx->amount;
            } else {
                $totalExpense += (float)$trx->amount;
                $currentRunningBalance -= (float)$trx->amount;
            }

            $trx->running_balance = $currentRunningBalance;
            $trx->formatted_running_balance = 'Rp ' . number_format($currentRunningBalance, 0, ',', '.');
            $ledgerRows[] = $trx;
        }

        $endingBalance = $initialBalance + $totalIncome - $totalExpense;

        return [
            'period' => $period,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'formatted_start_date' => Carbon::parse($startDate)->translatedFormat('d F Y'),
            'formatted_end_date' => Carbon::parse($endDate)->translatedFormat('d F Y'),
            'initial_balance' => $initialBalance,
            'formatted_initial_balance' => 'Rp ' . number_format($initialBalance, 0, ',', '.'),
            'total_income' => $totalIncome,
            'formatted_total_income' => 'Rp ' . number_format($totalIncome, 0, ',', '.'),
            'total_expense' => $totalExpense,
            'formatted_total_expense' => 'Rp ' . number_format($totalExpense, 0, ',', '.'),
            'net_change' => $totalIncome - $totalExpense,
            'formatted_net_change' => 'Rp ' . number_format($totalIncome - $totalExpense, 0, ',', '.'),
            'ending_balance' => $endingBalance,
            'formatted_ending_balance' => 'Rp ' . number_format($endingBalance, 0, ',', '.'),
            'transactions' => $ledgerRows,
            'transaction_count' => count($ledgerRows),
        ];
    }

    /**
     * Resolve date range from preset period or custom inputs.
     */
    protected function resolveDateRange(string $period, ?string $customStart = null, ?string $customEnd = null): array
    {
        return match($period) {
            'today' => [
                'start_date' => now()->toDateString(),
                'end_date' => now()->toDateString(),
            ],
            'last_month' => [
                'start_date' => now()->subMonth()->startOfMonth()->toDateString(),
                'end_date' => now()->subMonth()->endOfMonth()->toDateString(),
            ],
            'this_year' => [
                'start_date' => now()->startOfYear()->toDateString(),
                'end_date' => now()->endOfYear()->toDateString(),
            ],
            'custom' => [
                'start_date' => $customStart ?: now()->startOfMonth()->toDateString(),
                'end_date' => $customEnd ?: now()->toDateString(),
            ],
            default => [ // this_month
                'start_date' => now()->startOfMonth()->toDateString(),
                'end_date' => now()->endOfMonth()->toDateString(),
            ],
        };
    }
}
