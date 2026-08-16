<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class AppointmentConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Appointment Confirmed',
            'body' => "{$this->appointment->patient_name} confirmed for {$this->appointment->appt_date->format('d M Y')} at ".
                Carbon::parse($this->appointment->appt_time)->format('h:i A').'.',
            'icon' => 'fa-calendar-check',
            'url' => route('appointments.index'),
        ];
    }
}
