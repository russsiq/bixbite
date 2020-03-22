<?php

namespace App\Http\Middleware;

// Базовые расширения PHP.
use Closure;

// Сторонние зависимости.
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @param  Request  $request
     * @param  Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, string $role)
    {
        $this->verifyUserByRole($request->user(), $role);

        return $next($request);
    }

    /**
     * Проверить пользователя по группе пользователей.
     * @param  User|null  $user
     * @param  string  $role
     * @return bool
     */
    protected function verifyUserByRole(?User $user, string $role): bool
    {
        if ($user instanceof User && $user->hasRole($role)) {
            return true;
        }

        $this->untrusted();
    }

    /**
     * Обработать запрос от ненадежного пользователя.
     * @return void
     *
     * @throws HttpException
     */
    protected function untrusted()
    {
        abort(403, trans('common.error.403.message'), [
            'X-Robots-Tag' => 'noindex, nofollow',

        ]);
    }
}
