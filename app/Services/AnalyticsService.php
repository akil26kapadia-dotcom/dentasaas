<?php

namespace App\Services;

use App\Models\Clinic;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    protected function periodRange(string $period): array
    {
        return match ($period) {
            'last_3_months' => [now()->subMonths(3)->startOfDay(), now()->endOfDay()],
            'this_year' => [now()->startOfYear(), now()->endOfDay()],
            default => [now()->startOfMonth(), now()->endOfDay()],
        };
    }

    public function getStats(Clinic $clinic, string $period = 'this_month'): array
    {
        return Cache::remember("analytics.{$clinic->id}.stats.{$period}", 600, function () use ($clinic, $period) {
            [$start, $end] = $this->periodRange($period);

            $invoices = $clinic->invoices()->whereBetween('invoice_date', [$start, $end]);
            $invoiceCount = (clone $invoices)->count();
            $invoiceTotal = (clone $invoices)->sum('grand_total');

            return [
                'total_revenue' => (float) (clone $invoices)->where('status', 'paid')->sum('grand_total'),
                'total_patients' => $clinic->patients()->whereBetween('created_at', [$start, $end])->count(),
                'appointments_count' => $clinic->appointments()->whereBetween('appt_date', [$start, $end])->count(),
                'avg_invoice_value' => $invoiceCount > 0 ? round((float) $invoiceTotal / $invoiceCount, 2) : 0.0,
            ];
        });
    }

    public function getMonthlyRevenue(Clinic $clinic, int $months = 12): array
    {
        return Cache::remember("analytics.{$clinic->id}.monthly_revenue.{$months}", 600, function () use ($clinic, $months) {
            $labels = [];
            $data = [];

            for ($i = $months - 1; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $labels[] = $month->format('M Y');
                $data[] = (float) $clinic->invoices()
                    ->where('status', 'paid')
                    ->whereMonth('invoice_date', $month->month)
                    ->whereYear('invoice_date', $month->year)
                    ->sum('grand_total');
            }

            return compact('labels', 'data');
        });
    }

    public function getAppointmentTrend(Clinic $clinic, int $days = 30): array
    {
        return Cache::remember("analytics.{$clinic->id}.appointment_trend.{$days}", 600, function () use ($clinic, $days) {
            $labels = [];
            $data = [];

            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('d M');
                $data[] = $clinic->appointments()->whereDate('appt_date', $date)->count();
            }

            return compact('labels', 'data');
        });
    }

    public function getStatusBreakdown(Clinic $clinic): array
    {
        return Cache::remember("analytics.{$clinic->id}.status_breakdown", 600, function () use ($clinic) {
            $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

            // Aggregation permitted to use raw SQL for analytics per coding standards.
            $counts = $clinic->appointments()
                ->selectRaw('status, count(*) as aggregate_count')
                ->groupBy('status')
                ->pluck('aggregate_count', 'status');

            return [
                'labels' => array_map('ucfirst', $statuses),
                'data' => collect($statuses)->map(fn ($status) => (int) ($counts[$status] ?? 0))->all(),
            ];
        });
    }

    public function getTopServices(Clinic $clinic, int $limit = 5): array
    {
        return Cache::remember("analytics.{$clinic->id}.top_services.{$limit}", 600, function () use ($clinic, $limit) {
            return $clinic->invoices()
                ->pluck('items')
                ->flatten(1)
                ->filter()
                ->groupBy('service')
                ->map(fn ($group, $service) => [
                    'service_name' => $service,
                    'count' => (int) $group->sum('qty'),
                    'revenue' => (float) $group->sum(fn ($item) => ($item['qty'] ?? 1) * ($item['price'] ?? 0)),
                ])
                ->sortByDesc('revenue')
                ->take($limit)
                ->values()
                ->all();
        });
    }
}
