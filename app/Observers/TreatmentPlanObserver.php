<?php

namespace App\Observers;

use App\Models\TreatmentSession;

class TreatmentPlanObserver
{
    public function saved(TreatmentSession $session): void
    {
        $plan = $session->plan;

        if (! $plan || $plan->status === 'completed' || $plan->total_sessions <= 0) {
            return;
        }

        $completedSessions = $plan->sessions()->where('status', 'completed')->count();

        if ($completedSessions >= $plan->total_sessions) {
            $plan->update(['status' => 'completed']);
        }
    }
}
