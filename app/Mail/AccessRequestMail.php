<?php

namespace App\Mail;

use App\Models\AccessRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccessRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AccessRequest $accessRequest) {}

    public function build(): self
    {
        return $this
            ->subject('New Access Request - '.$this->accessRequest->clinic_name)
            ->view('emails.access-request');
    }
}
