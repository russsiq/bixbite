<?php

namespace App\Models\Mutators;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

/**
 * @property-read ?string $logined Get the difference in a human readable format in the current locale.
 * @property-read string $profile
 */
trait UserMutators
{
    public function getAvatarAttribute()
    {
        return get_avatar($this->email, $this->attributes['avatar']);
    }

    /**
     * Get the difference in a human readable format in the current locale.
     *
     * @return string|null
     */
    public function getCreatedAttribute(): ?string
    {
        if ($this->created_at instanceof Carbon) {
            return $this->created_at->diffForHumans();
        }

        return null;
    }

    public function getLoginedAttribute()
    {
        return is_null($this->logined_at) ? null : $this->asDateTime($this->logined_at)->diffForHumans();
    }

    public function getProfileAttribute(): ?string
    {
        return $this->exists ? route('profile.show', $this) : null;
    }

    // Role is guarded, but F12 and final. Remember about safety.
    public function setRoleAttribute($role)
    {
        // Если сайт на эксплуатации.
        if (App::environment('production')) {
            // И кто-то кроме собственника сайта
            // пытается изменить группу пользователя.
            if ('owner' !== user('role')) {
                // Сбрасываем ему группу до рядового пользователя.
                $role = 'user';
            }
        }

        $this->attributes['role'] = $role;
    }
}
