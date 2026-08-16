<?php

namespace App\Notifications;

use App\Models\AccessRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewAccessRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public AccessRequest $accessRequest) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'New Access Request',
            'body' => "{$this->accessRequest->name} from {$this->accessRequest->clinic_name} requested access.",
            'icon' => 'fa-user-plus',
            'url' => route('admin.access-requests.index'),
        ];
    }
}
