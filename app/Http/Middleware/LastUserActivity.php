<?php

namespace BBCMS\Http\Middleware;

use Closure;
use Auth;
use Cache;
use Carbon\Carbon;

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
            cache(['user-is-online-'.$request->user()->id => true], now()->addMinutes(15));
        }

        return $next($request);
    }
}
