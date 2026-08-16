<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureClinicIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $clinic = tenant();

        if (Auth::check() && (! $clinic || $clinic->status !== 'active')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Your clinic account is not active. Please contact support.',
            ]);
        }

        if ($clinic && $clinic->language) {
            App::setLocale($clinic->language);
        }

        return $next($request);
    }
}
