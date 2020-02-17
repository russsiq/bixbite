<?php

namespace BBCMS\Policies;

// Исключения.
use BadMethodCallException;

// Сторонние зависимости.
use BBCMS\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Базовый класс политик,
 * описывающий привилегии пользователей сайта.
 */
class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Проксирование политик, неопределенных в дочерних классах.
     * Разрешен полный доступ для собственника сайта.
     * @param  string  $method
     * @param  array  $parameters
     * @return bool
     *
     * @throws BadMethodCallException
     */
    public function __call(string $method, array $parameters): bool
    {
        $user = array_shift($parameters);

        if ($user instanceof User) {
            return $user->hasRole('owner');
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}
