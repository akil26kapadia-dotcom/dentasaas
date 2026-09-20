<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanonicalHost
{
    /**
     * Send every request on a non-canonical host (old subdomain, www.) to the canonical one.
     * Does nothing until APP_CANONICAL_HOST is set, so it can ship before the domain is live.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $canonical = config('dentasaas.canonical_host');

        if ($canonical && app()->environment('production') && strcasecmp($request->getHost(), $canonical) !== 0 && ! $request->is('up')) {
            $status = in_array($request->method(), ['GET', 'HEAD'], true) ? 301 : 308;

            return redirect()->away('https://'.$canonical.$request->getRequestUri(), $status);
        }

        return $next($request);
    }
}
