<?php

namespace App\Services;

use App\Mail\WelcomeMail;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CredentialMailer
{
    /**
     * Send login credentials by email, but never let an SMTP failure block the
     * underlying action (clinic/user creation, password reset, etc). The
     * outcome is flashed to the session so the admin UI can show the
     * credentials directly as a fallback when delivery fails.
     */
    public function send(User $recipient, Clinic $clinic, string $password): bool
    {
        $mailSent = true;

        try {
            Mail::to($recipient->email)->send(new WelcomeMail($clinic, $recipient, $password));
        } catch (Throwable $e) {
            Log::warning('Credential email failed to send.', [
                'recipient' => $recipient->email,
                'error' => $e->getMessage(),
            ]);
            $mailSent = false;
        }

        session()->flash('generated_credentials', [
            'name' => $recipient->name,
            'email' => $recipient->email,
            'password' => $password,
            'mail_sent' => $mailSent,
        ]);

        return $mailSent;
    }
}
