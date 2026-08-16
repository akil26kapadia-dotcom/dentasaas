<?php

namespace App\Services;

use App\Models\Clinic;
use Illuminate\Support\Collection;

class DashboardService
{
    public function __construct(protected PlanService $planService) {}

    public function getStats(Clinic $clinic): array
    {
        return [
            'total_patients' => $clinic->patients()->count(),
            'today_appointments' => $clinic->appointments()->whereDate('appt_date', today())->count(),
            'monthly_revenue' => $clinic->invoices()
                ->where('status', 'paid')
                ->whereMonth('invoice_date', now()->month)
                ->whereYear('invoice_date', now()->year)
                ->sum('grand_total'),
            'pending_count' => $clinic->appointments()->where('status', 'pending')->count(),
        ];
    }

    public function getTodayAppointments(Clinic $clinic): Collection
    {
        return $clinic->appointments()
            ->whereDate('appt_date', today())
            ->orderBy('appt_time')
            ->get();
    }

    public function getMonthlyRevenueChart(Clinic $clinic): array
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $data[] = (float) $clinic->invoices()
                ->where('status', 'paid')
                ->whereMonth('invoice_date', $month->month)
                ->whereYear('invoice_date', $month->year)
                ->sum('grand_total');
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function getPlanUsage(Clinic $clinic): array
    {
        $limits = $clinic->getPlanLimits();
        $usage = $this->planService->getUsage($clinic);

        $result = [];

        foreach ($usage as $resource => $used) {
            $limit = $limits[$resource] ?? 0;
            $pct = ($limit === -1 || $limit === 0) ? 0 : (int) round(($used / $limit) * 100);

            $result[$resource] = [
                'used' => $used,
                'limit' => $limit,
                'pct' => $limit === -1 ? 0 : min($pct, 100),
            ];
        }

        return $result;
    }
}
