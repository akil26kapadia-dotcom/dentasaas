<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClinicController extends Controller
{
    public function index(): View
    {
        $clinics = Clinic::withCount([
            'users as doctors_count' => fn ($query) => $query->where('role', '!=', 'superadmin'),
            'patients',
        ])
            ->orderBy('name')
            ->get();

        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.clinics.index', compact('clinics', 'plans'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.clinics.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'plan' => ['required', Rule::in(Plan::pluck('key'))],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ]);

        $clinic = Clinic::create([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'plan' => $validated['plan'],
            'plan_expires_at' => $validated['plan'] === 'free' ? null : today()->addDays(30),
            'status' => 'active',
        ]);

        $password = Str::password(12);

        $admin = User::create([
            'clinic_id' => $clinic->id,
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'password' => Hash::make($password),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Mail::to($admin->email)->send(new WelcomeMail($clinic, $admin, $password));

        return redirect()->route('admin.clinics.index')->with('success', 'Clinic created and admin invited.');
    }

    public function show(Clinic $clinic): RedirectResponse
    {
        return redirect()->route('admin.clinics.index');
    }

    public function edit(Clinic $clinic): RedirectResponse
    {
        return redirect()->route('admin.clinics.index');
    }

    public function update(Request $request, Clinic $clinic): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'gst' => ['nullable', 'string', 'max:50'],
            'plan' => ['required', Rule::in(Plan::pluck('key'))],
            'status' => ['required', 'in:active,inactive,pending'],
        ]);

        $clinic->update($validated);

        return redirect()->route('admin.clinics.index')->with('success', 'Clinic updated.');
    }

    public function destroy(Clinic $clinic): RedirectResponse
    {
        $clinic->delete();

        return redirect()->route('admin.clinics.index')->with('success', 'Clinic deleted.');
    }

    public function setPlan(Request $request, Clinic $clinic): RedirectResponse
    {
        $validated = $request->validate([
            'plan' => ['required', Rule::in(Plan::pluck('key'))],
        ]);

        $clinic->update([
            'plan' => $validated['plan'],
            'plan_expires_at' => $validated['plan'] === 'free' ? null : today()->addDays(30),
        ]);

        return redirect()->route('admin.clinics.index')->with('success', 'Plan updated.');
    }

    public function extendPlan(Clinic $clinic): RedirectResponse
    {
        $base = ($clinic->plan_expires_at && $clinic->plan_expires_at->isFuture())
            ? $clinic->plan_expires_at
            : today();

        $clinic->update(['plan_expires_at' => $base->copy()->addDays(30)]);

        return redirect()->route('admin.clinics.index')->with('success', 'Plan extended by 30 days.');
    }

    public function toggleStatus(Clinic $clinic): RedirectResponse
    {
        $clinic->update(['status' => $clinic->status === 'active' ? 'inactive' : 'active']);

        return redirect()->route('admin.clinics.index')->with('success', 'Clinic status updated.');
    }

    public function resetPassword(Clinic $clinic): RedirectResponse
    {
        $admin = $clinic->users()->where('role', 'admin')->first() ?? $clinic->users()->first();

        abort_unless($admin, 404, 'No admin user found for this clinic.');

        $password = Str::password(12);
        $admin->update(['password' => Hash::make($password)]);

        Mail::to($admin->email)->send(new WelcomeMail($clinic, $admin, $password));

        return redirect()->route('admin.clinics.index')->with('success', 'Password reset and emailed to clinic admin.');
    }

    protected function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $i = 1;

        while (Clinic::where('slug', $slug)->exists()) {
            $slug = $original.'-'.(++$i);
        }

        return $slug;
    }
}
