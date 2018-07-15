<?php

namespace BBCMS\Models\Mutators;

trait UserMutators
{
    public function getProfileAttribute()
    {
        return action('UsersController@profile', $this);
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

    public function setPasswordAttribute($value)
    {
        // If create new user.
        if (empty($this->id) and is_null($value)) {
            throw new \InvalidArgumentException(
                'Password must not be empty.'
            );
        }

        if (! is_null($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
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
