<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\PlanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(
        protected PlanService $planService,
        protected DashboardService $dashboardService,
    ) {}

    public function index(): View
    {
        $clinic = tenant();

        return view('settings.index', [
            'clinic' => $clinic,
            'planUsage' => $this->dashboardService->getPlanUsage($clinic),
            'daysUntilExpiry' => $this->planService->daysUntilExpiry($clinic),
        ]);
    }

    public function updateClinic(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'gst' => ['nullable', 'string', 'max:50'],
        ]);

        tenant()->update($validated);

        return redirect()->route('settings.index')->with('success', 'Clinic details updated.');
    }

    public function uploadLogo(Request $request): JsonResponse
    {
        $request->validate([
            'logo' => ['required', 'image', 'max:2048'],
        ]);

        $clinic = tenant();

        if ($clinic->logo_path) {
            Storage::disk('public')->delete($clinic->logo_path);
        }

        $path = $request->file('logo')->store("logos/{$clinic->id}", 'public');

        $clinic->update(['logo_path' => $path]);

        return response()->json(['logo_url' => Storage::disk('public')->url($path)]);
    }

    public function removeLogo(): JsonResponse
    {
        $clinic = tenant();

        if ($clinic->logo_path) {
            Storage::disk('public')->delete($clinic->logo_path);
            $clinic->update(['logo_path' => null]);
        }

        return response()->json(['logo_url' => null]);
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update(['password' => Hash::make($request->input('password'))]);

        return redirect()->route('settings.index')->with('success', 'Password updated.');
    }

    public function updateLanguage(Request $request): RedirectResponse
    {
        $request->validate([
            'language' => ['required', 'in:en,hi'],
        ]);

        tenant()->update(['language' => $request->input('language')]);

        return redirect()->route('settings.index')->with('success', 'Language updated.');
    }
}
