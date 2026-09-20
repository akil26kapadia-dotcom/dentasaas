<?php

use App\Http\Middleware\CanonicalHost;
use App\Http\Middleware\EnsureClinicIsActive;
use App\Http\Middleware\EnsurePlanLimit;
use App\Http\Middleware\NoIndexPrivate;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SuperAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'clinic.active' => EnsureClinicIsActive::class,
            'plan.limit' => EnsurePlanLimit::class,
            'superadmin' => SuperAdmin::class,
        ]);

        $middleware->web(prepend: [CanonicalHost::class]);
        $middleware->web(append: [SecurityHeaders::class, NoIndexPrivate::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
