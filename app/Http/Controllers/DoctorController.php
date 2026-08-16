<?php

namespace App\Http\Controllers;

use App\Exceptions\PlanLimitException;
use App\Http\Requests\DoctorRequest;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Services\PlanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class DoctorController extends Controller
{
    public function __construct(protected PlanService $planService) {}

    public function index(): View
    {
        $clinic = tenant();

        $doctors = User::where('clinic_id', $clinic->id)
            ->where('role', '!=', 'superadmin')
            ->orderBy('name')
            ->get();

        $usage = $this->planService->getUsage($clinic);
        $limits = $clinic->getPlanLimits();

        return view('doctors.index', [
            'doctors' => $doctors,
            'doctorsUsed' => $usage['doctors'],
            'doctorsLimit' => $limits['doctors'] ?? 0,
        ]);
    }

    public function store(DoctorRequest $request): RedirectResponse|Response
    {
        $clinic = tenant();

        try {
            $this->planService->checkLimit($clinic, 'doctors');
        } catch (PlanLimitException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 403);
            }

            return redirect()->route('doctors.index')->withErrors(['plan_limit' => $e->getMessage()]);
        }

        $password = Str::password(12);

        $doctor = User::create([
            ...$request->validated(),
            'clinic_id' => $clinic->id,
            'password' => Hash::make($password),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Mail::to($doctor->email)->send(new WelcomeMail($clinic, $doctor, $password));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($doctor, 201);
        }

        return redirect()->route('doctors.index')->with('success', 'Doctor added and invited via email.');
    }

    public function update(DoctorRequest $request, User $doctor): RedirectResponse|Response
    {
        abort_unless($doctor->clinic_id === tenant()->id, 404);

        $data = $request->validated();

        if ($doctor->id === auth()->id()) {
            unset($data['role']);
        }

        $doctor->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($doctor);
        }

        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Request $request, User $doctor): RedirectResponse|Response
    {
        abort_unless($doctor->clinic_id === tenant()->id, 404);
        abort_if($doctor->id === auth()->id(), 403, 'You cannot remove your own account.');

        $doctor->update(['is_active' => false]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Doctor deactivated.']);
        }

        return redirect()->route('doctors.index')->with('success', 'Doctor deactivated.');
    }

    public function toggleActive(User $doctor): RedirectResponse
    {
        abort_unless($doctor->clinic_id === tenant()->id, 404);

        $doctor->update(['is_active' => ! $doctor->is_active]);

        return redirect()->route('doctors.index')->with('success', 'Doctor status updated.');
    }
}
