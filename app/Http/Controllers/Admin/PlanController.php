<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(): View
    {
        $plans = Plan::withCount('clinics')->orderBy('sort_order')->get();

        return view('admin.plans.index', compact('plans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        Plan::create($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan created.');
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $validated = $this->validated($request, $plan);

        if ($plan->clinics()->exists()) {
            $validated['key'] = $plan->key;
        }

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        if ($plan->key === 'free') {
            return redirect()->route('admin.plans.index')->with('error', 'The Free plan cannot be deleted.');
        }

        if ($plan->clinics()->exists()) {
            return redirect()->route('admin.plans.index')->with('error', 'Cannot delete a plan that clinics are currently using.');
        }

        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted.');
    }

    public function toggle(Plan $plan): RedirectResponse
    {
        if ($plan->key === 'free') {
            return redirect()->route('admin.plans.index')->with('error', 'The Free plan cannot be deactivated.');
        }

        $plan->update(['is_active' => ! $plan->is_active]);

        return redirect()->route('admin.plans.index')->with('success', 'Plan availability updated.');
    }

    protected function validated(Request $request, ?Plan $plan = null): array
    {
        return $request->validate([
            'key' => [
                'required', 'string', 'max:30', 'alpha_dash:ascii',
                Rule::unique('plans', 'key')->ignore($plan?->id),
            ],
            'name' => ['required', 'string', 'max:60'],
            'price_monthly' => ['required', 'integer', 'min:0'],
            'patients_limit' => ['required', 'integer', 'min:-1'],
            'appointments_limit' => ['required', 'integer', 'min:-1'],
            'invoices_limit' => ['required', 'integer', 'min:-1'],
            'doctors_limit' => ['required', 'integer', 'min:-1'],
            'pdf_export' => ['sometimes', 'boolean'],
            'prescriptions' => ['sometimes', 'boolean'],
            'analytics' => ['required', 'in:none,basic,full'],
            'is_highlighted' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);
    }
}
