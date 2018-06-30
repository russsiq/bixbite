<?php

namespace BBCMS\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if ($request->user() and $request->user()->hasRole($role)) {
            return $next($request);
        }

        if ($request->ajax() or $request->wantsJson()) {
            return response('Unauthorized.', 401);
        }
        
        return redirect()->back()->withErrors(['Unauthorized.']);
    }

}
