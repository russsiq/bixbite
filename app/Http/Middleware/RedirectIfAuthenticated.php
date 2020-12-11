<?php

namespace App\Http\Middleware;

// Базовые расширения PHP.
use Closure;

// Зарегистрированные фасады приложения.
use Illuminate\Support\Facades\Auth;

// Сторонние зависимости.
use App\Providers\RouteServiceProvider;
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
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
