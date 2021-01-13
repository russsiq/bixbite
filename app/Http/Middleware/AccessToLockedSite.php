<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

/**
 * Проверка на доступ к заблокированному сайту.
 * В данный момент не используется.
 */
class AccessToLockedSite
{
    /**
     * Обработка входящего запроса.
     *
     * @param  Request  $request
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
