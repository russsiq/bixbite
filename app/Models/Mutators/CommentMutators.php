<?php

namespace App\Models\Mutators;

trait CommentMutators
{
    /**
     * Not used because it generates additional queries.
     * @see App\Models\Collections\CommentCollection
     */
    // public function getByAuthorAttribute(): bool {}

    public function getUrlAttribute(): ?string
    {
        if ($parentUrl = $this->commentable->url) {
            return "{$parentUrl}#comment-{$this->id}";
        }

        return null;
    }

    public function getCreatedAttribute()
    {
        return is_null($this->created_at) ? null : $this->asDateTime($this->created_at)->diffForHumans();
    }

    public function getByUserAttribute(): bool
    {
        return is_int($this->user_id);
    }

    public function getIsApprovedAttribute(bool $value): bool
    {
        if (setting('comments.moderate', true)) {
            return (bool) $value;
        }

        return true;
    }
}
