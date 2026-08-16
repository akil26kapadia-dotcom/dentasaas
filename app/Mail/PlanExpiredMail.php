<?php

namespace App\Mail;

use App\Models\Clinic;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlanExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Clinic $clinic, public string $expiredPlan) {}

    public function build(): self
    {
        return $this
            ->subject('Your DentaSaaS plan has expired')
            ->view('emails.plan-expired');
    }
}
