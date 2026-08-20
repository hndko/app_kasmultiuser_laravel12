<?php

namespace App\Http\Controllers\Modules\Report;

use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\CashCategory;
use App\Services\Modules\Report\CashReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashReportController extends Controller
{
    public function __construct(
        protected CashReportService $reportService
    ) {}

    /**
     * Display the cash flow report.
     */
    public function index(Request $request): View
    {
        $filters = $request->only('period', 'start_date', 'end_date', 'type', 'cash_category_id');
        $reportData = $this->reportService->generateCashReport($filters);
        $categories = CashCategory::orderBy('name')->get();

        return view('modules.reports.cash.index', [
            'report' => $reportData,
            'categories' => $categories,
            'types' => TransactionType::cases(),
            'filters' => $filters,
            'title' => 'Laporan Kas',
        ]);
    }

    /**
     * Display a printable version of the cash report.
     */
    public function print(Request $request): View
    {
        $filters = $request->only('period', 'start_date', 'end_date', 'type', 'cash_category_id');
        $reportData = $this->reportService->generateCashReport($filters);

        return view('modules.reports.cash.print', [
            'report' => $reportData,
            'filters' => $filters,
            'title' => 'Cetak Laporan Kas - ' . $reportData['formatted_start_date'] . ' s/d ' . $reportData['formatted_end_date'],
        ]);
    }
}
