<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function start(Clinic $clinic): RedirectResponse
    {
        $target = $clinic->users()->where('role', 'admin')->where('is_active', true)->first()
            ?? $clinic->users()->where('is_active', true)->first();

        abort_unless($target, 404, 'No active user found for this clinic.');

        session(['impersonator_id' => Auth::id()]);
        Auth::login($target);

        return redirect()->route('dashboard')->with('success', "You're now viewing DentaSaaS as {$clinic->name}.");
    }

    public function stop(): RedirectResponse
    {
        $impersonatorId = session('impersonator_id');

        abort_unless($impersonatorId, 403);

        $admin = User::where('id', $impersonatorId)->where('role', 'superadmin')->first();

        abort_unless($admin, 403);

        session()->forget('impersonator_id');
        Auth::login($admin);

        return redirect()->route('admin.dashboard')->with('success', 'Returned to Super Admin.');
    }
}
