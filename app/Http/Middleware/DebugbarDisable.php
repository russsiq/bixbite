<?php

namespace BBCMS\Http\Middleware;

use Closure;

class DebugbarDisable
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
        // \Debugbar::disable();
        // \Debugbar::enable();

        \Debugbar::disable();

        return $next($request);
    }
}
