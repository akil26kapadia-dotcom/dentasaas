<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use App\Services\PlanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __construct(
        protected AnalyticsService $analyticsService,
        protected PlanService $planService,
    ) {}

    public function index(Request $request): View|RedirectResponse
    {
        $clinic = tenant();

        if (! $this->planService->isFeatureAllowed($clinic, 'analytics')) {
            return redirect()->route('dashboard')->with('warning', 'Analytics is not available on your current plan. Upgrade to unlock it.');
        }

        $period = $request->input('period', 'this_month');
        $level = $clinic->getPlanLimits()['analytics'] ?? false;

        $stats = $this->analyticsService->getStats($clinic, $period);
        $monthlyRevenue = $this->analyticsService->getMonthlyRevenue($clinic);

        $appointmentTrend = null;
        $statusBreakdown = null;
        $topServices = null;

        if ($level === 'full') {
            $appointmentTrend = $this->analyticsService->getAppointmentTrend($clinic);
            $statusBreakdown = $this->analyticsService->getStatusBreakdown($clinic);
            $topServices = $this->analyticsService->getTopServices($clinic);
        }

        return view('analytics.index', compact(
            'stats', 'monthlyRevenue', 'appointmentTrend', 'statusBreakdown', 'topServices', 'period', 'level'
        ));
    }
}
