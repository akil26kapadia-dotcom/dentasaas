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

    public function getStatTrends(Clinic $clinic): array
    {
        $lastMonth = now()->subMonthNoOverflow();

        $thisMonthPatients = $clinic->patients()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $lastMonthPatients = $clinic->patients()->whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->count();

        $thisMonthAppts = $clinic->appointments()->whereMonth('appt_date', now()->month)->whereYear('appt_date', now()->year)->count();
        $lastMonthAppts = $clinic->appointments()->whereMonth('appt_date', $lastMonth->month)->whereYear('appt_date', $lastMonth->year)->count();

        $thisMonthRevenue = (float) $clinic->invoices()->where('status', 'paid')->whereMonth('invoice_date', now()->month)->whereYear('invoice_date', now()->year)->sum('grand_total');
        $lastMonthRevenue = (float) $clinic->invoices()->where('status', 'paid')->whereMonth('invoice_date', $lastMonth->month)->whereYear('invoice_date', $lastMonth->year)->sum('grand_total');

        $thisMonthPending = $clinic->appointments()->where('status', 'pending')->whereMonth('appt_date', now()->month)->whereYear('appt_date', now()->year)->count();
        $lastMonthPending = $clinic->appointments()->where('status', 'pending')->whereMonth('appt_date', $lastMonth->month)->whereYear('appt_date', $lastMonth->year)->count();

        return [
            'total_patients' => $this->pctChange($lastMonthPatients, $thisMonthPatients),
            'today_appointments' => $this->pctChange($lastMonthAppts, $thisMonthAppts),
            'monthly_revenue' => $this->pctChange($lastMonthRevenue, $thisMonthRevenue),
            'pending_count' => $this->pctChange($lastMonthPending, $thisMonthPending),
        ];
    }

    public function getRecentActivity(Clinic $clinic, int $limit = 10): Collection
    {
        $patients = $clinic->patients()->latest()->take($limit)->get()->map(fn ($p) => [
            'icon' => 'fa-user-plus',
            'description' => "New patient added: {$p->name}",
            'created_at' => $p->created_at,
        ]);

        $appointments = $clinic->appointments()->latest()->take($limit)->get()->map(fn ($a) => [
            'icon' => 'fa-calendar-check',
            'description' => "Appointment booked for {$a->patient_name}",
            'created_at' => $a->created_at,
        ]);

        $invoices = $clinic->invoices()->latest()->take($limit)->get()->map(fn ($i) => [
            'icon' => 'fa-file-invoice',
            'description' => "Invoice {$i->invoice_no} created for {$i->patient_name}",
            'created_at' => $i->created_at,
        ]);

        return $patients->concat($appointments)->concat($invoices)
            ->sortByDesc('created_at')
            ->take($limit)
            ->values();
    }

    protected function pctChange(float $previous, float $current): float
    {
        if ($previous == 0.0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
