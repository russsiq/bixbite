<?php

namespace App\Models\Mutators;

trait CommentMutators
{
    public function getUrlAttribute(): string
    {
        return $this->commentable->url.'#comment-'.$this->id;
    }

    public function getCreatedAttribute()
    {
        return is_null($this->created_at) ? null : $this->asDateTime($this->created_at)->diffForHumans();
    }

    public function getByUserAttribute(): bool
    {
        return is_int($this->user_id);
    }

    // Don't touch this. Need when comments added from ajax
    public function getAuthorAttribute()
    {
        $by_user = $this->by_user;

        return (object) [
            'name' => $by_user ? $this->user->name : $this->author_name,
            'profile' => $by_user ? $this->user->profile : $this->author_name,
            'avatar' => $by_user ? $this->user->avatar : get_avatar($this->author_email),
            'isOnline' => $by_user ? $this->user->isOnline() : false,
        ];
    }
}
