<?php

namespace App\Http\Controllers;

use App\Exceptions\PlanLimitException;
use App\Http\Requests\PatientRequest;
use App\Models\Patient;
use App\Services\PlanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PatientController extends Controller
{
    public function __construct(protected PlanService $planService) {}

    public function index(Request $request): View
    {
        $patients = Patient::query()
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->input('q');
                $query->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($request->filled('gender'), fn ($query) => $query->where('gender', $request->input('gender')))
            ->when($request->filled('blood_group'), fn ($query) => $query->where('blood_group', $request->input('blood_group')))
            ->latest()
            ->get();

        return view('patients.index', compact('patients'));
    }

    public function create(): View
    {
        $patient = new Patient;

        return view('patients.create', compact('patient'));
    }

    public function store(PatientRequest $request): RedirectResponse|Response
    {
        try {
            $this->planService->checkLimit(tenant(), 'patients');
        } catch (PlanLimitException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 403);
            }

            return redirect()->back()->withInput()->withErrors(['plan_limit' => $e->getMessage()]);
        }

        $patient = Patient::create($request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($patient, 201);
        }

        return redirect()->route('patients.show', $patient)->with('success', 'Patient added successfully.');
    }

    public function show(Patient $patient): View
    {
        $patient->load(['appointments', 'invoices', 'prescriptions', 'treatmentPlans.sessions']);

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient): View
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(PatientRequest $request, Patient $patient): RedirectResponse|Response
    {
        $patient->update($request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($patient);
        }

        return redirect()->route('patients.show', $patient)->with('success', 'Patient updated successfully.');
    }

    public function destroy(Request $request, Patient $patient): RedirectResponse|Response
    {
        $patient->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Patient deleted.']);
        }

        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }
}
