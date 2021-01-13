<?php

namespace App\Http\Middleware;

use Closure;

/**
 * [CheckBanned description].
 *
 * @source https://laraveldaily.com/how-to-ban-suspend-users-in-laravel-project/
 */
class CheckBanned
{
    /**
     * Обработка входящего запроса.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->banned_until && now()->lessThan(auth()->user()->banned_until)) {
            $banned_days = now()->diffInDays(auth()->user()->banned_until);
            auth()->logout();

            if ($banned_days > 14) {
                $message = 'Your account has been suspended. Please contact administrator.';
            } else {
                $message = 'Your account has been suspended for '.$banned_days.' '.str_plural('day', $banned_days).'. Please contact administrator.';
            }

            return redirect()->route('login')->withMessage($message);
        }

        return $next($request);
    }
}
