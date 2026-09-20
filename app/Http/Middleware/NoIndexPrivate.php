<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoIndexPrivate
{
    /** Paths that must never appear in search results: the apps and every auth screen. */
    protected array $patterns = [
        'admin', 'admin/*', 'dashboard', 'patients', 'patients/*', 'doctors', 'doctors/*',
        'appointments', 'appointments/*', 'services', 'services/*', 'invoices', 'invoices/*',
        'prescriptions', 'prescriptions/*', 'treatment-plans', 'treatment-plans/*', 'analytics',
        'settings', 'settings/*', 'notifications', 'notifications/*', 'profile', 'impersonate/*',
        'login', 'logout', 'register', 'forgot-password', 'reset-password', 'reset-password/*',
        'verify-email', 'verify-email/*', 'email/*', 'confirm-password', 'password', 'password/*',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->is($this->patterns)) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive');
        }

        return $response;
    }
}
