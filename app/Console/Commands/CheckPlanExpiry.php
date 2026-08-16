<?php

namespace App\Console\Commands;

use App\Mail\PlanExpiredMail;
use App\Models\Clinic;
use App\Notifications\PlanExpiryNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class CheckPlanExpiry extends Command
{
    protected $signature = 'dentasaas:check-plan-expiry';

    protected $description = 'Downgrade clinics with an expired paid plan to Free, and warn clinics expiring within 7 days.';

    public function handle(): int
    {
        $expiredClinics = Clinic::where('plan', '!=', 'free')
            ->whereNotNull('plan_expires_at')
            ->whereDate('plan_expires_at', '<', today())
            ->get();

        foreach ($expiredClinics as $clinic) {
            $expiredPlan = $clinic->plan;

            $clinic->update(['plan' => 'free']);

            $recipient = $clinic->email ?: $clinic->users()->where('role', 'admin')->value('email');

            if ($recipient) {
                Mail::to($recipient)->send(new PlanExpiredMail($clinic, $expiredPlan));
            }

            $this->info("Downgraded {$clinic->name} from {$expiredPlan} to free.");
        }

        $expiringSoon = Clinic::where('plan', '!=', 'free')
            ->whereNotNull('plan_expires_at')
            ->whereDate('plan_expires_at', '>=', today())
            ->whereDate('plan_expires_at', '<=', today()->addDays(7))
            ->get();

        foreach ($expiringSoon as $clinic) {
            $daysLeft = (int) today()->diffInDays($clinic->plan_expires_at, false);
            $admins = $clinic->users()->where('role', 'admin')->get();

            Notification::send($admins, new PlanExpiryNotification($clinic, $daysLeft));
        }

        $this->info('Plan expiry check complete: '.$expiredClinics->count().' downgraded, '.$expiringSoon->count().' warned.');

        return self::SUCCESS;
    }
}
