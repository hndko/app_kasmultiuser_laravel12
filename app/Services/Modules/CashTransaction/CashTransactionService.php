<?php

namespace App\Services\Modules\CashTransaction;

use App\Enums\TransactionType;
use App\Models\CashTransaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class CashTransactionService
{
    /**
     * Build base query with filters.
     */
    public function buildFilteredQuery(array $filters = []): Builder
    {
        $query = CashTransaction::query()->with(['category', 'creator', 'updater']);

        // Keyword Search
        if (!empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function (Builder $q) use ($search) {
                $q->where('transaction_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhereHas('category', function (Builder $cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('creator', function (Builder $uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Type Filter (income / expense)
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Category Filter
        if (!empty($filters['cash_category_id'])) {
            $query->where('cash_category_id', $filters['cash_category_id']);
        }

        // Date Range Filter
        if (!empty($filters['start_date'])) {
            $query->whereDate('transaction_date', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('transaction_date', '<=', $filters['end_date']);
        }

        return $query;
    }

    /**
     * Get paginated transactions.
     */
    public function getPaginatedTransactions(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->buildFilteredQuery($filters)
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Get transaction financial summary for the filtered criteria.
     */
    public function getTransactionSummary(array $filters = []): array
    {
        $income = (float) (clone $this->buildFilteredQuery($filters))
            ->where('type', TransactionType::INCOME)
            ->sum('amount');

        $expense = (float) (clone $this->buildFilteredQuery($filters))
            ->where('type', TransactionType::EXPENSE)
            ->sum('amount');

        $balance = $income - $expense;

        return [
            'total_income' => $income,
            'total_expense' => $expense,
            'net_balance' => $balance,
            'formatted_income' => 'Rp ' . number_format($income, 0, ',', '.'),
            'formatted_expense' => 'Rp ' . number_format($expense, 0, ',', '.'),
            'formatted_balance' => 'Rp ' . number_format($balance, 0, ',', '.'),
        ];
    }

    /**
     * Generate unique transaction number TRX-YYYYMMDD-XXXX.
     */
    public function generateTransactionNumber(?string $date = null): string
    {
        $dateObj = $date ? Carbon::parse($date) : now();
        $dateStr = $dateObj->format('Ymd');
        $prefix = "TRX-{$dateStr}-";

        $count = CashTransaction::where('transaction_number', 'like', "{$prefix}%")->withTrashed()->count() + 1;
        $number = sprintf('%s%04d', $prefix, $count);

        while (CashTransaction::where('transaction_number', $number)->withTrashed()->exists()) {
            $count++;
            $number = sprintf('%s%04d', $prefix, $count);
        }

        return $number;
    }

    /**
     * Create a new cash transaction.
     */
    public function createTransaction(array $data, int $userId): CashTransaction
    {
        $transactionNumber = $this->generateTransactionNumber($data['transaction_date'] ?? null);

        return CashTransaction::create([
            'transaction_number' => $transactionNumber,
            'transaction_date' => $data['transaction_date'],
            'type' => $data['type'],
            'cash_category_id' => $data['cash_category_id'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'reference' => $data['reference'] ?? null,
            'created_by' => $userId,
            'updated_by' => null,
        ]);
    }

    /**
     * Update an existing cash transaction.
     */
    public function updateTransaction(CashTransaction $transaction, array $data, int $userId): CashTransaction
    {
        $transaction->update([
            'transaction_date' => $data['transaction_date'],
            'type' => $data['type'],
            'cash_category_id' => $data['cash_category_id'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'reference' => $data['reference'] ?? null,
            'updated_by' => $userId,
        ]);

        return $transaction;
    }

    /**
     * Soft delete a cash transaction.
     */
    public function deleteTransaction(CashTransaction $transaction): bool
    {
        return (bool) $transaction->delete();
    }
}
