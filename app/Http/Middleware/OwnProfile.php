<?php

namespace BBCMS\Http\Middleware;

use Closure;

/**
 * Проверка на то, что пользователь пытается получить доступ к своему профилю.
 * Главным образом используется в маршрутах с префиксом `profile`.
 */
class OwnProfile
{
    /**
     * Обработка входящего запроса.
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() and $request->user()->id === $request->route('user')->id) {
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
