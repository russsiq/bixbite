<?php

namespace BBCMS\Http\Middleware;

use Closure;

use BBCMS\Models\Privilege;

/**
 * Проверка на то, что Группы пользователей закэшированы.
 */
class RolesIsCached
{
    /**
     * Обработка входящего запроса.
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check the existence of the cache.
        if (! cache()->has('roles')) {
            Privilege::getModel()->roles();
        }

        return $next($request);
    }
}
