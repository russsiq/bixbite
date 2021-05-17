<?php

// -----------------------------------------------------------------------------
//  Для обновления значений в кэше используется Посредник (middleware):
// -----------------------------------------------------------------------------
// if ($user = $request->user()) {
//     cache()->put($user->isOnlineKey(), now(),
//         now()->addSeconds($user->isOnlineMinutes() * 60)
//     );
// }

namespace App\Models\Traits;

trait hasOnline
{
    /**
     * Время, в течении которого считается,
     * что пользователь находится онлайн.
     * @var int
     */
    protected $isOnlineMinutes = 15;

    /**
     * Префикс для ключа в кэше.
     * @var string
     */
    protected $isOnlinePrefix = 'users.is-online-';

    /**
     * Формирование динамического атрибута,
     * возвращающего индикацию, что пользователь онлайн.
     * @return bool
     */
    public function getIsOnlineAttribute(): bool
    {
        return $this->exists ? $this->isOnline() : false;
    }

    /**
     * Проверить, находится ли пользователь онлайн.
     * @return bool
     */
    public function isOnline(): bool
    {
        return cache()->has($this->isOnlineKey());
    }

    /**
     * Получить ключ, по которому
     * выполняется проверка пользователя на онлайн.
     * @return string
     */
    public function isOnlineKey(): string
    {
        return $this->isOnlinePrefix.$this->id;
    }

    /**
     * Получить время, в течении которого считается,
     * что пользователь находится онлайн.
     * @return int
     */
    public function isOnlineMinutes(): int
    {
        return setting('users.online_minutes', $this->isOnlineMinutes);
    }

    /**
     * Получить время, когда пользователь
     * последний раз проявлял активность.
     * @return string|null
     */
    public function lastActive()
    {
        return $this->isOnline()
            ? cache($this->isOnlineKey())->diffForHumans()
            : $this->logined;
    }
}
