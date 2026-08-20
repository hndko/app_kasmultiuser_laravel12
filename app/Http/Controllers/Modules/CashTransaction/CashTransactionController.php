<?php

namespace App\Http\Controllers\Modules\CashTransaction;

use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\CashTransaction\StoreCashTransactionRequest;
use App\Http\Requests\Modules\CashTransaction\UpdateCashTransactionRequest;
use App\Models\CashCategory;
use App\Models\CashTransaction;
use App\Services\Modules\CashTransaction\CashTransactionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashTransactionController extends Controller
{
    public function __construct(
        protected CashTransactionService $transactionService
    ) {}

    /**
     * Display a listing of cash transactions.
     */
    public function index(Request $request): View
    {
        $filters = $request->only('search', 'type', 'cash_category_id', 'start_date', 'end_date');
        $transactions = $this->transactionService->getPaginatedTransactions($filters, 10);
        $summary = $this->transactionService->getTransactionSummary($filters);
        $categories = CashCategory::orderBy('name')->get();

        return view('modules.cash-transactions.index', [
            'transactions' => $transactions,
            'summary' => $summary,
            'categories' => $categories,
            'types' => TransactionType::cases(),
            'filters' => $filters,
            'title' => 'Transaksi Kas',
        ]);
    }

    /**
     * Show the form for creating a new cash transaction.
     */
    public function create(Request $request): View
    {
        $selectedType = $request->get('type', TransactionType::INCOME->value);
        $categories = CashCategory::active()->orderBy('name')->get();
        $suggestedNumber = $this->transactionService->generateTransactionNumber();

        return view('modules.cash-transactions.create', [
            'categories' => $categories,
            'types' => TransactionType::cases(),
            'selectedType' => $selectedType,
            'suggestedNumber' => $suggestedNumber,
            'title' => 'Tambah Transaksi Kas',
        ]);
    }

    /**
     * Store a newly created cash transaction in storage.
     */
    public function store(StoreCashTransactionRequest $request): RedirectResponse
    {
        $this->transactionService->createTransaction(
            $request->validated(),
            auth()->id()
        );

        return redirect()->route('modules.cash.transactions.index')
            ->with('success', 'Transaksi kas berhasil dicatat.');
    }

    /**
     * Display the specified cash transaction.
     */
    public function show(CashTransaction $transaction): View
    {
        $transaction->load(['category', 'creator', 'updater']);

        return view('modules.cash-transactions.show', [
            'transaction' => $transaction,
            'title' => 'Detail Transaksi: ' . $transaction->transaction_number,
        ]);
    }

    /**
     * Show the form for editing the specified cash transaction.
     */
    public function edit(CashTransaction $transaction): View
    {
        $categories = CashCategory::orderBy('name')->get();

        return view('modules.cash-transactions.edit', [
            'transaction' => $transaction,
            'categories' => $categories,
            'types' => TransactionType::cases(),
            'title' => 'Edit Transaksi: ' . $transaction->transaction_number,
        ]);
    }

    /**
     * Update the specified cash transaction in storage.
     */
    public function update(UpdateCashTransactionRequest $request, CashTransaction $transaction): RedirectResponse
    {
        $this->transactionService->updateTransaction(
            $transaction,
            $request->validated(),
            auth()->id()
        );

        return redirect()->route('modules.cash.transactions.index')
            ->with('success', 'Transaksi kas berhasil diperbarui.');
    }

    /**
     * Remove the specified cash transaction from storage.
     */
    public function destroy(CashTransaction $transaction): RedirectResponse
    {
        $this->transactionService->deleteTransaction($transaction);

        return redirect()->route('modules.cash.transactions.index')
            ->with('success', 'Transaksi kas berhasil dihapus.');
    }
}
