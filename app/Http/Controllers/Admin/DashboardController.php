<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessRequest;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $planPrices = Plan::pluck('price_monthly', 'key');
        $paidClinics = Clinic::where('plan', '!=', 'free')->get(['plan']);

        $stats = [
            'total_clinics' => Clinic::count(),
            'active_clinics' => Clinic::where('status', 'active')->count(),
            'free_count' => Clinic::where('plan', 'free')->count(),
            'paid_count' => $paidClinics->count(),
            'mrr' => $paidClinics->sum(fn ($clinic) => (int) ($planPrices[$clinic->plan] ?? 0)),
            'new_this_week' => Clinic::where('created_at', '>=', now()->subWeek())->count(),
            'total_users' => User::where('role', '!=', 'superadmin')->count(),
            'pending_requests' => AccessRequest::where('status', 'pending')->count(),
        ];

        $recentClinics = Clinic::latest()->take(8)->get();

        $pendingRequests = AccessRequest::where('status', 'pending')->latest()->take(5)->get();

        $paidWithExpiry = Clinic::where('plan', '!=', 'free')->whereNotNull('plan_expires_at');
        $expiringSoon = (clone $paidWithExpiry)
            ->whereBetween('plan_expires_at', [today(), today()->addDays(14)->endOfDay()])
            ->orderBy('plan_expires_at')
            ->get();
        $expiredCount = (clone $paidWithExpiry)->where('plan_expires_at', '<', today())->count();

        return view('admin.dashboard', compact('stats', 'recentClinics', 'pendingRequests', 'expiringSoon', 'expiredCount'));
    }
}
