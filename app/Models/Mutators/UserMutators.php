<?php

namespace App\Models\Mutators;

use Illuminate\Support\Facades\App;

trait UserMutators
{
    public function getProfileAttribute()
    {
        return route('profile.show', $this);
    }

    public function getUrlAttribute()
    {
        return $this->profile;
    }

    public function getCreatedAttribute()
    {
        return is_null($this->created_at) ? null : $this->asDateTime($this->created_at)->diffForHumans();
    }

    public function getLoginedAttribute()
    {
        return is_null($this->logined_at) ? null : $this->asDateTime($this->logined_at)->diffForHumans();
    }

    public function getAvatarAttribute()
    {
        return get_avatar($this->email, $this->attributes['avatar']);
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

    /**
     * Get a non-existing attribute $entity->comment_store_action for html-form.
     * @return string
     */
    public function getCommentStoreActionAttribute()
    {
        return route('comments.store', [$this->getMorphClass(), $this->id]);
    }
}
