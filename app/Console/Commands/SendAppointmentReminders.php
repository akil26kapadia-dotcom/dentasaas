<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\AppointmentConfirmedNotification;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'dentasaas:send-appointment-reminders';

    protected $description = 'Send a reminder notification for every confirmed appointment scheduled for tomorrow.';

    public function handle(): int
    {
        $appointments = Appointment::with('doctor')
            ->where('status', 'confirmed')
            ->whereDate('appt_date', today()->addDay())
            ->get();

        foreach ($appointments as $appointment) {
            $appointment->doctor?->notify(new AppointmentConfirmedNotification($appointment));
        }

        $this->info("Sent {$appointments->count()} appointment reminder(s) for tomorrow.");

        return self::SUCCESS;
    }
}
