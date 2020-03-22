<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Отключение панели отладки для некоторых маршрутов.
 *
 * Также можно сделать на включение???
 * Только передавать булевого значение.
 */
class DebugbarDisable
{
    /**
     * Обработка входящего запроса.
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
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
