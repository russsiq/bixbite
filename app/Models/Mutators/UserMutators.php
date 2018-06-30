<?php

namespace BBCMS\Models\Mutators;

trait UserMutators
{
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

    // public function setRoleAttribute($value)
    // {
    //     $this->attributes['role'] = $value ?? 'user';
    // }

    public function setPasswordAttribute($value)
    {
        // If create new user
        if (empty($this->id) and is_null($value)) {
            throw new \InvalidArgumentException(
                sprintf('Password must not be empty.')
            );
        }

        if (! is_null($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }
}
