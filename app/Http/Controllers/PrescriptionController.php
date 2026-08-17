<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrescriptionRequest;
use App\Models\Prescription;
use App\Services\PlanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PrescriptionController extends Controller
{
    public function __construct(protected PlanService $planService) {}

    public function index(): View
    {
        $prescriptions = Prescription::latest('rx_date')->get();

        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create(): View
    {
        $clinic = tenant();

        $patients = $clinic->patients()->orderBy('name')->get(['id', 'name', 'phone']);
        $doctors = $clinic->users()->where('is_active', true)->where('role', '!=', 'superadmin')->orderBy('name')->get(['id', 'name']);

        return view('prescriptions.create', compact('patients', 'doctors'));
    }

    public function store(PrescriptionRequest $request): RedirectResponse
    {
        $clinic = tenant();

        if (! $this->planService->isFeatureAllowed($clinic, 'prescriptions')) {
            return redirect()->route('prescriptions.create')
                ->withInput()
                ->withErrors(['plan_limit' => 'Prescriptions are not available on your current plan.']);
        }

        Prescription::create($request->validated());

        return redirect()->route('prescriptions.index')->with('success', 'Prescription created successfully.');
    }

    public function pdf(Prescription $prescription): Response
    {
        $clinic = tenant();

        abort_unless($this->planService->isFeatureAllowed($clinic, 'pdf'), 403, 'PDF export is not available on your current plan.');

        $pdf = Pdf::loadView('pdf.prescription', [
            'prescription' => $prescription,
            'clinic' => $clinic,
            'doctorName' => $prescription->doctor_name ?: Auth::user()->name,
        ])->setPaper('a4');

        $filename = "prescription-{$prescription->id}.pdf";

        return request()->boolean('print')
            ? $pdf->stream($filename)
            : $pdf->download($filename);
    }

    public function destroy(Prescription $prescription): RedirectResponse
    {
        $prescription->delete();

        return redirect()->route('prescriptions.index')->with('success', 'Prescription deleted successfully.');
    }
}
