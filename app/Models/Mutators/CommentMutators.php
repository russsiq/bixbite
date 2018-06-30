<?php

namespace BBCMS\Models\Mutators;

trait CommentMutators
{
    public function setUpdatedAtAttribute($value)
    {
        return 'null';
    }

    public function getCreatedAttribute()
    {
        return is_null($this->created_at) ? null : $this->asDateTime($this->created_at)->diffForHumans();
    }

    public function getByUserAttribute()
    {
        return is_int($this->user_id);
    }

    // Don't touch this. Need when comments added from ajax
    public function getAuthorAttribute()
    {
        return (object) [
            'name' => is_int($this->user_id) ? $this->user->name : $this->name,
            'avatar' => is_int($this->user_id) ? $this->user->avatar : get_avatar($this->email),
            'isOnline' => is_int($this->user_id) ? $this->user->isOnline() : false,
        ];
    }
}
