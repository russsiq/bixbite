<?php

namespace BBCMS\Http\Middleware;

use Closure;

class LastUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()) {
            cache()->put($request->user()->isOnlineKey(), true,
                now()->addMinutes($request->user()->isOnlineMinutes())
            );
        }

        return $next($request);
    }
}
