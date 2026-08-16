<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessRequest;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_clinics' => Clinic::count(),
            'active_clinics' => Clinic::where('status', 'active')->count(),
            'free_count' => Clinic::where('plan', 'free')->count(),
            'paid_count' => Clinic::where('plan', '!=', 'free')->count(),
            'new_this_week' => Clinic::where('created_at', '>=', now()->subWeek())->count(),
            'total_users' => User::where('role', '!=', 'superadmin')->count(),
            'pending_requests' => AccessRequest::where('status', 'pending')->count(),
        ];

        $recentClinics = Clinic::latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentClinics'));
    }
}
