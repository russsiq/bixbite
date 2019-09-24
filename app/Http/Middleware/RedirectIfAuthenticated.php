<?php

namespace BBCMS\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Перенаправление на главную страницу сайта со страницы,
 * которая доступна только гостям сайта,
 * т.е. неаутентифицированным пользователям.
 */
class RedirectIfAuthenticated
{
    /**
     * Обработка входящего запроса.
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/');
        }

        return $next($request);
    }
}
