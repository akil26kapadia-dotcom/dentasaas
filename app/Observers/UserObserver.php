<?php

namespace App\Observers;

use Illuminate\Auth\Events\Login;

class UserObserver
{
    public function login(Login $event): void
    {
        $event->user->update(['last_login_at' => now()]);
    }
}
