<?php

namespace App\Services;

use App\Exceptions\PlanLimitException;
use App\Models\Clinic;
use Illuminate\Support\Carbon;

class PlanService
{
    public function getUsage(Clinic $clinic): array
    {
        return [
            'patients' => $clinic->patients()->count(),
            'appointments' => $clinic->appointments()
                ->whereMonth('appt_date', now()->month)
                ->whereYear('appt_date', now()->year)
                ->count(),
            'invoices' => $clinic->invoices()
                ->whereMonth('invoice_date', now()->month)
                ->whereYear('invoice_date', now()->year)
                ->count(),
            'doctors' => $clinic->users()->whereIn('role', ['admin', 'doctor'])->count(),
        ];
    }

    public function checkLimit(Clinic $clinic, string $resource): void
    {
        $limits = $clinic->getPlanLimits();
        $limit = $limits[$resource] ?? null;

        if ($limit === null || $limit === -1) {
            return;
        }

        $usage = $this->getUsage($clinic);
        $used = $usage[$resource] ?? 0;

        if ($used >= $limit) {
            throw new PlanLimitException($resource, $limit);
        }
    }

    public function isFeatureAllowed(Clinic $clinic, string $feature): bool
    {
        $limits = $clinic->getPlanLimits();

        return (bool) ($limits[$feature] ?? false);
    }

    public function isExpired(Clinic $clinic): bool
    {
        if (! $clinic->plan_expires_at) {
            return false;
        }

        return Carbon::parse($clinic->plan_expires_at)->isPast();
    }

    public function daysUntilExpiry(Clinic $clinic): ?int
    {
        if (! $clinic->plan_expires_at) {
            return null;
        }

        return (int) now()->diffInDays(Carbon::parse($clinic->plan_expires_at), false);
    }
}
