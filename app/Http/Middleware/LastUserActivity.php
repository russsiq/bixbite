<?php

namespace BBCMS\Http\Middleware;

use Closure;

/**
 * Запись в кэш информации о том, что пользователь находится онлайн.
 */
class LastUserActivity
{
    /**
     * Обработка входящего запроса.
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($user = $request->user()) {
            cache()->put($user->isOnlineKey(), now(),
                now()->addMinutes($user->isOnlineMinutes())
            );
        }

        return $next($request);
    }
}
