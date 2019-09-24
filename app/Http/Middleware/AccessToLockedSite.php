<?php

namespace BBCMS\Http\Middleware;

use Closure;

/**
 * Проверка на доступ к заблокированному сайту.
 * В данный момент не используется.
 */
class AccessToLockedSite
{
    /**
     * Обработка входящего запроса.
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);

        if ($request->user() and $request->user()->hasRole($role)) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response(
                __('common.error.403.message'), 403
            );
        }

        abort(403, __('common.error.403.message'), [
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }
}
