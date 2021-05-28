<?php

namespace App\Models\Traits;

/**
 * @property-read bool $is_online
 * @property-read string|null $last_active
 *
 * @see \App\Http\Middleware\MustBeOnlineMiddleware
 */
trait MustBeOnlineTrait
{
    /**
     * Время, в течении которого считается,
     * что пользователь находится онлайн.
     *
     * @var integer
     */
    protected $isOnlineMinutes = 15;

    /**
     * Префикс для ключа в кэше.
     *
     * @var string
     */
    protected $isOnlinePrefix = 'users.is-online-';

    /**
     * Fix user activity.
     *
     * @return void
     */
    public function fixActivity(): void
    {
        if ($this->exists) {
            cache()->put(
                $this->isOnlineKey(),
                now(),
                now()->addMinutes($this->isOnlineMinutes())
            );
        }
    }

    /**
     * Формирование динамического атрибута,
     * возвращающего индикацию, что пользователь онлайн.
     *
     * @return bool
     */
    public function getIsOnlineAttribute(): bool
    {
        return $this->exists ? $this->isOnline() : false;
    }

    /**
     * Получить время, когда пользователь
     * последний раз проявлял активность.
     *
     * @return string|null
     */
    public function getLastActiveAttribute(): ?string
    {
        return $this->isOnline()
            ? cache($this->isOnlineKey())->diffForHumans()
            : $this->logined;
    }

    /**
     * Проверить, находится ли пользователь онлайн.
     *
     * @return bool
     */
    protected function isOnline(): bool
    {
        return cache()->has($this->isOnlineKey());
    }

    /**
     * Получить ключ, по которому
     * выполняется проверка пользователя на онлайн.
     *
     * @return string
     */
    protected function isOnlineKey(): string
    {
        return $this->isOnlinePrefix.$this->id;
    }

    /**
     * Получить время, в течении которого считается,
     * что пользователь находится онлайн.
     *
     * @return integer
     */
    protected function isOnlineMinutes(): int
    {
        return $this->setting->online_minutes($this->isOnlineMinutes);
    }
}
