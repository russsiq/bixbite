<?php

namespace App\Models\Mutators;

trait CommentMutators
{
    public function getUrlAttribute()
    {
        return $this->commentable->url ? $this->commentable->url.'#comment-'.$this->id : null;
    }

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
        $by_user = $this->by_user;

        return (object) [
            'name' => $by_user ? $this->user->name : $this->name,
            'profile' => $by_user ? $this->user->profile : $this->name,
            'avatar' => $by_user ? $this->user->avatar : get_avatar($this->email),
            'isOnline' => $by_user ? $this->user->isOnline() : false,
        ];
    }
}
