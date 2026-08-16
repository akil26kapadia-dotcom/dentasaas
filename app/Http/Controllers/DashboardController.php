<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    public function index(): View
    {
        $clinic = tenant();

        $stats = $this->dashboardService->getStats($clinic);
        $trends = $this->dashboardService->getStatTrends($clinic);
        $chart = $this->dashboardService->getMonthlyRevenueChart($clinic);
        $planUsage = $this->dashboardService->getPlanUsage($clinic);
        $todayAppointments = $this->dashboardService->getTodayAppointments($clinic);
        $recentActivity = $this->dashboardService->getRecentActivity($clinic);

        return view('dashboard.index', compact(
            'stats', 'trends', 'chart', 'planUsage', 'todayAppointments', 'recentActivity'
        ));
    }
}
