<?php

namespace App\Http\Middleware;

// Базовые расширения PHP.
use Closure;

// Сторонние зависимости.
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Проверка на то, что пользователь пытается получить доступ к своему профилю.
 * Главным образом используется в маршрутах с префиксом `profile`.
 */
class OwnProfile
{
    /**
     * Обработка входящего запроса.
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->verifyUserById($request->user(), $request->route('user')->id);

        return $next($request);
    }

    /**
     * Проверить пользователя по идентификатору.
     * @param  ?User  $user
     * @param  string  $role
     * @return bool
     */
    protected function verifyUserById(?User $user, int $id): bool
    {
        if ($user instanceof User and $user->id === $id) {
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
