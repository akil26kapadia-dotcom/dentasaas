<?php

namespace App\Mail;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Clinic $clinic,
        public User $user,
        public string $password,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Your DentaSaaS account is ready')
            ->view('emails.welcome');
    }
}
