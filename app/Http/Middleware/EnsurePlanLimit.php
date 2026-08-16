<?php

namespace App\Http\Middleware;

use App\Exceptions\PlanLimitException;
use App\Services\PlanService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlanLimit
{
    public function __construct(protected PlanService $planService) {}

    public function handle(Request $request, Closure $next, string $resource): Response
    {
        $clinic = tenant();

        if ($clinic) {
            try {
                $this->planService->checkLimit($clinic, $resource);
            } catch (PlanLimitException $e) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'message' => $e->getMessage(),
                        'resource' => $e->resource,
                        'limit' => $e->limit,
                    ], 403);
                }

                return redirect()->back()->withErrors(['plan_limit' => $e->getMessage()]);
            }
        }

        return $next($request);
    }
}
