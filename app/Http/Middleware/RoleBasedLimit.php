<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class RoleBasedLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userRole = auth()->user()->role;

        if($userRole == 'admin'){
            RateLimiter::for('api', function (Request $request) {
                return Limit::perMinute(1000)->by($request->user()?->id ?: $request->ip());
            });
        }elseif($userRole == 'premium:'){
            RateLimiter::for('api', function (Request $request) {
                return Limit::perMinute(500)->by($request->user()?->id ?: $request->ip());
            });
        }else{
            RateLimiter::for('api', function (Request $request) {
                return Limit::perMinute(100)->by($request->user()?->id ?: $request->ip());
            });
        }

        return $next($request);
    }
}
