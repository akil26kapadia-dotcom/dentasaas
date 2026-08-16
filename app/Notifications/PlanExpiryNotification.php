<?php

namespace App\Notifications;

use App\Models\Clinic;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PlanExpiryNotification extends Notification
{
    use Queueable;

    public function __construct(public Clinic $clinic, public int $daysLeft) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Plan Expiring Soon',
            'body' => "Your {$this->clinic->plan} plan expires in {$this->daysLeft} days. Renew now.",
            'icon' => 'fa-triangle-exclamation',
            'url' => route('settings.index'),
        ];
    }
}
