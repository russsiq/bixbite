<?php

namespace BBCMS\Http\Middleware;

use Closure;

/**
 * Проверка на то, что текущий пользователь
 * относится к переданной группе пользователей.
 * Главным образом используется в маршрутах
 * с префиксом `app_common`, таких как оптимизаторы системы.
 */
class CheckRole
{
    /**
     * Обработка входящего запроса.
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, string $role)
    {
        if ($user = $request->user() and $user->hasRole($role)) {
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
