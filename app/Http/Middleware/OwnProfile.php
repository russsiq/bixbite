<?php

namespace BBCMS\Http\Middleware;

use Closure;

class OwnProfile
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() and $request->user()->id === $request->route('user')->id) {
            return $next($request);
        }

        if ($request->ajax() or $request->wantsJson()) {
            return response(
                __('common.error.403.message'), 403
            );
        }

        abort(403, __('common.error.403.message'), [
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }

}
