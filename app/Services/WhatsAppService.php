<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\TreatmentPlan;
use App\Models\TreatmentSession;

class WhatsAppService
{
    public function appointmentConfirmation(Appointment $appointment, Clinic $clinic): string
    {
        $message = "Hello {$appointment->patient_name}, your appointment at *{$clinic->name}* is confirmed.\n".
            "*Date:* {$appointment->appt_date->format('d M Y')} *Time:* {$appointment->appt_time} *Service:* {$appointment->service_name}. Arrive 5 min early. 🦷";

        return $this->buildUrl($appointment->patient?->phone, $message);
    }

    public function treatmentReminder(TreatmentSession $session, TreatmentPlan $plan, Clinic $clinic): string
    {
        $message = "Hello {$plan->patient_name}, this is a reminder for your *{$plan->treatment}* session at *{$clinic->name}*.\n".
            "*Session:* {$session->title} *Date:* ".($session->scheduled_date?->format('d M Y') ?? 'TBD').' *Time:* '.($session->scheduled_time ?? 'TBD').'. 🦷';

        return $this->buildUrl($plan->patient?->phone, $message);
    }

    public function buildUrl(?string $phone, string $message): string
    {
        $phone = $phone ? preg_replace('/\D/', '', $phone) : '';

        return 'https://wa.me/'.$phone.'?text='.urlencode($message);
    }
}
