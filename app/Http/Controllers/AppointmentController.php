<?php

namespace App\Http\Controllers;

use App\Exceptions\PlanLimitException;
use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Services\PlanService;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function __construct(
        protected PlanService $planService,
        protected WhatsAppService $whatsAppService,
    ) {}

    public function index(Request $request): View
    {
        $clinic = tenant();

        $appointments = Appointment::query()
            ->with('patient')
            ->when($request->filled('date'), function ($query) use ($request) {
                $date = $request->string('date') === 'today' ? today() : $request->string('date');
                $query->whereDate('appt_date', $date);
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->integer('user_id')))
            ->orderBy('appt_date')
            ->orderBy('appt_time')
            ->get()
            ->each(function (Appointment $appointment) use ($clinic) {
                $appointment->whatsapp_url = $this->whatsAppService->appointmentConfirmation($appointment, $clinic);
            });

        $counts = [
            'all' => $clinic->appointments()->count(),
            'today' => $clinic->appointments()->whereDate('appt_date', today())->count(),
            'pending' => $clinic->appointments()->where('status', 'pending')->count(),
            'confirmed' => $clinic->appointments()->where('status', 'confirmed')->count(),
        ];

        $patients = $clinic->patients()->orderBy('name')->get(['id', 'name', 'phone']);
        $services = $clinic->services()->where('is_active', true)->orderBy('name')->get(['id', 'name', 'price']);
        $doctors = $clinic->users()->where('is_active', true)->where('role', '!=', 'superadmin')->orderBy('name')->get(['id', 'name']);

        return view('appointments.index', compact('appointments', 'counts', 'patients', 'services', 'doctors'));
    }

    public function store(AppointmentRequest $request): JsonResponse
    {
        $clinic = tenant();

        try {
            $this->planService->checkLimit($clinic, 'appointments');
        } catch (PlanLimitException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }

        $appointment = Appointment::create([
            ...$request->validated(),
            'status' => $request->input('status', 'pending'),
        ]);

        $appointment->load('patient');

        return response()->json([
            'success' => true,
            'appointment' => $appointment,
            'whatsapp_url' => $this->whatsAppService->appointmentConfirmation($appointment, $clinic),
        ], 201);
    }

    public function update(AppointmentRequest $request, Appointment $appointment): JsonResponse
    {
        $appointment->update($request->validated());

        return response()->json(['success' => true, 'appointment' => $appointment]);
    }

    public function updateStatus(Request $request, Appointment $appointment): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $appointment->update(['status' => $request->string('status')]);

        return response()->json(['success' => true, 'appointment' => $appointment]);
    }

    public function destroy(Appointment $appointment): JsonResponse
    {
        $appointment->delete();

        return response()->json(['success' => true]);
    }
}
