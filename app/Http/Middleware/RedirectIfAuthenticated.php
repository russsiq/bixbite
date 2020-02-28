<?php

namespace BBCMS\Http\Middleware;

// Базовые расширения PHP.
use Closure;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\Auth;

// Сторонние зависимости.
use BBCMS\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

/**
 * Перенаправление на главную страницу сайта со страницы,
 * которая доступна только гостям сайта,
 * т.е. неаутентифицированным пользователям.
 */
class RedirectIfAuthenticated
{
    /**
     * Обработка входящего запроса.
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
