<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Plan;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(): View
    {
        $plans = Plan::orderBy('sort_order')->get();

        $activePaidClinics = Clinic::where('status', 'active')
            ->where('plan', '!=', 'free')
            ->get();

        $mrr = $activePaidClinics->sum(fn (Clinic $clinic) => $plans->firstWhere('key', $clinic->plan)?->price_monthly ?? 0);

        $planDistribution = $plans->map(fn (Plan $plan) => [
            'plan' => $plan,
            'count' => Clinic::where('plan', $plan->key)->count(),
        ]);

        $expiringSoon = Clinic::where('plan', '!=', 'free')
            ->whereNotNull('plan_expires_at')
            ->whereBetween('plan_expires_at', [today(), today()->addDays(7)])
            ->orderBy('plan_expires_at')
            ->get();

        $expired = Clinic::where('plan', '!=', 'free')
            ->whereNotNull('plan_expires_at')
            ->where('plan_expires_at', '<', today())
            ->orderBy('plan_expires_at')
            ->get();

        $stats = [
            'mrr' => $mrr,
            'active_paid_clinics' => $activePaidClinics->count(),
            'avg_revenue' => $activePaidClinics->count() > 0 ? round($mrr / $activePaidClinics->count()) : 0,
            'expiring_soon' => $expiringSoon->count(),
        ];

        $growth = $this->growthChart();

        return view('admin.billing.index', compact('stats', 'planDistribution', 'expiringSoon', 'expired', 'growth'));
    }

    protected function growthChart(): array
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $data[] = Clinic::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
