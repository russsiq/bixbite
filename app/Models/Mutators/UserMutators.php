<?php

namespace App\Models\Mutators;

trait UserMutators
{
    public function getProfileAttribute()
    {
        return route('profile', $this);
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
    public function setRoleAttribute($value)
    {
        $this->attributes['role'] = ('owner' == user('role')) ? $value : 'user';
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
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
