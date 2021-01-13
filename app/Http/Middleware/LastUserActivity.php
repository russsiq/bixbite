<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

/**
 * Запись в кэш информации о том, что пользователь находится онлайн.
 */
class LastUserActivity
{
    /**
     * Обработка входящего запроса.
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->updateUserInformation($request->user());

        return $next($request);
    }

    /**
     * Обноваить информацию о текущем пользователе.
     * @param  ?User  $user
     * @return void
     */
    protected function updateUserInformation(?User $user)
    {
        if ($user instanceof User) {
            cache()->put(
                $user->isOnlineKey(),
                now(),
                now()->addSeconds($user->isOnlineMinutes() * 60)
            );
        }
    }
}
