<?php

namespace App\Services\Modules\Dashboard;

use App\Enums\TransactionType;
use App\Models\CashCategory;
use App\Models\CashTransaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get primary dashboard summary metrics.
     */
    public function getDashboardMetrics(): array
    {
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        // Overall balance
        $totalIncomeOverall = (float) CashTransaction::where('type', TransactionType::INCOME)->sum('amount');
        $totalExpenseOverall = (float) CashTransaction::where('type', TransactionType::EXPENSE)->sum('amount');
        $totalBalance = $totalIncomeOverall - $totalExpenseOverall;

        // This Month Metrics
        $monthIncome = (float) CashTransaction::where('type', TransactionType::INCOME)
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthExpense = (float) CashTransaction::where('type', TransactionType::EXPENSE)
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthTrxCount = CashTransaction::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->count();

        return [
            'total_balance' => $totalBalance,
            'formatted_total_balance' => 'Rp ' . number_format($totalBalance, 0, ',', '.'),
            'month_income' => $monthIncome,
            'formatted_month_income' => 'Rp ' . number_format($monthIncome, 0, ',', '.'),
            'month_expense' => $monthExpense,
            'formatted_month_expense' => 'Rp ' . number_format($monthExpense, 0, ',', '.'),
            'month_net' => $monthIncome - $monthExpense,
            'formatted_month_net' => 'Rp ' . number_format($monthIncome - $monthExpense, 0, ',', '.'),
            'month_trx_count' => $monthTrxCount,
        ];
    }

    /**
     * Get monthly income & expense trend for last N months.
     */
    public function getMonthlyCashflowTrend(int $months = 6): array
    {
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthObj = now()->subMonths($i);
            $startDate = $monthObj->copy()->startOfMonth()->toDateString();
            $endDate = $monthObj->copy()->endOfMonth()->toDateString();

            $labels[] = $monthObj->translatedFormat('M Y');

            $inc = (float) CashTransaction::where('type', TransactionType::INCOME)
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');

            $exp = (float) CashTransaction::where('type', TransactionType::EXPENSE)
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->sum('amount');

            $incomeData[] = $inc;
            $expenseData[] = $exp;
        }

        return [
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }

    /**
     * Get top expense categories this month.
     */
    public function getCategoryBreakdown(string $type = 'expense', int $limit = 5): array
    {
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $results = DB::table('cash_transactions')
            ->join('cash_categories', 'cash_transactions.cash_category_id', '=', 'cash_categories.id')
            ->where('cash_transactions.type', $type)
            ->whereNull('cash_transactions.deleted_at')
            ->whereBetween('cash_transactions.transaction_date', [$startOfMonth, $endOfMonth])
            ->select('cash_categories.name', DB::raw('SUM(cash_transactions.amount) as total_amount'))
            ->groupBy('cash_categories.id', 'cash_categories.name')
            ->orderByDesc('total_amount')
            ->limit($limit)
            ->get();

        return $results->map(function ($item) {
            return [
                'name' => $item->name,
                'amount' => (float)$item->total_amount,
                'formatted_amount' => 'Rp ' . number_format($item->total_amount, 0, ',', '.'),
            ];
        })->toArray();
    }

    /**
     * Get recent transactions.
     */
    public function getRecentTransactions(int $limit = 5): Collection
    {
        return CashTransaction::with(['category', 'creator'])
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }
}
