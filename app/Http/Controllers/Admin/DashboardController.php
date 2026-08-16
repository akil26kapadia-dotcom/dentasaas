<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessRequest;
use App\Models\Clinic;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_clinics' => Clinic::count(),
            'active_clinics' => Clinic::where('status', 'active')->count(),
            'pending_requests' => AccessRequest::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
