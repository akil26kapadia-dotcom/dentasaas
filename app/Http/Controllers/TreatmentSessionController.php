<?php

namespace App\Http\Controllers;

use App\Http\Requests\TreatmentSessionRequest;
use App\Models\TreatmentPlan;
use App\Models\TreatmentSession;
use Illuminate\Http\JsonResponse;

class TreatmentSessionController extends Controller
{
    public function update(TreatmentSessionRequest $request, TreatmentPlan $plan, TreatmentSession $session): JsonResponse
    {
        abort_unless($session->plan_id === $plan->id, 404);

        $session->update($request->validated());

        return response()->json(['success' => true, 'session' => $session->fresh(), 'plan' => $plan->fresh()]);
    }
}
