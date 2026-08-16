<?php

namespace App\Providers;

use App\Models\TreatmentSession;
use App\Observers\TreatmentPlanObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(Login::class, [UserObserver::class, 'login']);

        TreatmentSession::observe(TreatmentPlanObserver::class);
    }
}
