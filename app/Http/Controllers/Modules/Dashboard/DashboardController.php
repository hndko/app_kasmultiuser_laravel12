<?php

namespace App\Http\Controllers\Modules\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Modules\Dashboard\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService
    ) {}

    /**
     * Display the application dashboard.
     */
    public function index(): View
    {
        $metrics = $this->dashboardService->getDashboardMetrics();
        $cashflowTrend = $this->dashboardService->getMonthlyCashflowTrend(6);
        $topExpenses = $this->dashboardService->getCategoryBreakdown('expense', 5);
        $recentTransactions = $this->dashboardService->getRecentTransactions(5);

        return view('modules.dashboard.index', [
            'metrics' => $metrics,
            'cashflowTrend' => $cashflowTrend,
            'topExpenses' => $topExpenses,
            'recentTransactions' => $recentTransactions,
            'title' => 'Dashboard Kas',
        ]);
    }
}
