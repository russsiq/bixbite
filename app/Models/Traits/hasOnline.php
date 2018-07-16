<?php

// -----------------------------------------------------------------------------
//  The tagging to activity of the user. Use in Middleware:
// -----------------------------------------------------------------------------
// if ($user = $request->user()) {
//     cache()->put($user->isOnlineKey(), now(),
//         now()->addMinutes($user->isOnlineMinutes())
//     );
// }

namespace BBCMS\Models\Traits;

trait hasOnline
{
    protected $isOnlinePrefix = 'users.is-online-';
    protected $isOnlineMinutes = 15;

    /**
     * Check if user is online.
     * @return bool
     */
    public function isOnline(): bool
    {
        return cache()->has($this->isOnlineKey());
    }

    /**
     * Get key to check if user is online.
     * @return string
     */
    public function isOnlineKey(): string
    {
        return $this->isOnlinePrefix.$this->id;
    }

    /**
     * Time in minutes when user can be considered online.
     * @return int
     */
    public function isOnlineMinutes(): int
    {
        return setting('users.online_minutes', $this->isOnlineMinutes);
    }

    /**
     * Last time when user was active.
     * @return string|null
     */
    public function lastActive()
    {
        return $this->isOnline()
            ? cache($this->isOnlineKey())->diffForHumans()
            : $this->logined;
    }
}
