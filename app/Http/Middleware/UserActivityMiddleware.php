<?php

namespace App\Http\Middleware;

use App\Jobs\UpdateUserLastActiveAtJob;
use Closure;
use Illuminate\Http\Request;

class UserActivityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check()){
            return $next($request);
        }

        UpdateUserLastActiveAtJob::dispatchAfterResponse(auth()->user());

        return $next($request);
    }
}
