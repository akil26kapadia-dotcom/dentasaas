<?php

namespace App\Http\Controllers;

use App\Http\Requests\TreatmentPlanRequest;
use App\Models\TreatmentPlan;
use App\Models\TreatmentSession;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TreatmentPlanController extends Controller
{
    public function __construct(protected WhatsAppService $whatsAppService) {}

    public function index(): View
    {
        $clinic = tenant();

        $plans = TreatmentPlan::with('sessions')->latest()->get();

        $counts = [
            'total' => $plans->count(),
            'active' => $plans->whereIn('status', ['planned', 'in_progress'])->count(),
            'done' => $plans->where('status', 'completed')->count(),
        ];

        $patients = $clinic->patients()->orderBy('name')->get(['id', 'name', 'phone']);
        $doctors = $clinic->users()->where('is_active', true)->where('role', '!=', 'superadmin')->orderBy('name')->get(['id', 'name']);

        return view('treatment-plans.index', compact('plans', 'counts', 'patients', 'doctors'));
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('treatment-plans.index');
    }

    public function store(TreatmentPlanRequest $request): RedirectResponse
    {
        $plan = TreatmentPlan::create($request->validated());

        for ($i = 1; $i <= $plan->total_sessions; $i++) {
            TreatmentSession::create([
                'clinic_id' => $plan->clinic_id,
                'plan_id' => $plan->id,
                'session_no' => $i,
                'title' => "Visit {$i}",
                'doctor_name' => $plan->doctor_name,
                'status' => 'planned',
            ]);
        }

        return redirect()->route('treatment-plans.index')->with('success', 'Treatment plan created successfully.');
    }

    public function show(TreatmentPlan $plan): JsonResponse
    {
        $clinic = tenant();

        $plan->load(['sessions' => fn ($query) => $query->orderBy('session_no'), 'patient']);

        $plan->sessions->each(function (TreatmentSession $session) use ($plan, $clinic) {
            $session->whatsapp_url = $this->whatsAppService->treatmentReminder($session, $plan, $clinic);
        });

        return response()->json($plan);
    }

    public function edit(TreatmentPlan $plan): RedirectResponse
    {
        return redirect()->route('treatment-plans.index');
    }

    public function update(TreatmentPlanRequest $request, TreatmentPlan $plan): RedirectResponse
    {
        $plan->update($request->validated());

        return redirect()->route('treatment-plans.index')->with('success', 'Treatment plan updated successfully.');
    }

    public function destroy(TreatmentPlan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('treatment-plans.index')->with('success', 'Treatment plan deleted successfully.');
    }

    public function drag(Request $request, TreatmentPlan $plan): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:planned,in_progress,completed,cancelled'],
        ]);

        $plan->update(['status' => $request->string('status')]);

        return response()->json(['success' => true, 'plan' => $plan]);
    }
}
