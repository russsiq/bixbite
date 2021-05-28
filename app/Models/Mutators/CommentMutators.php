<?php

namespace App\Models\Mutators;

use Illuminate\Support\Carbon;

/**
 * @property-read boolean $by_user
 * @property-read ?string $created
 * @property-read boolean $is_approved
 * @property-read ?string $url
 */
trait CommentMutators
{
    /**
     * Determine that the comment was created by an author of commentable model.
     *
     * @ignore Not used because it generates additional queries
     * @see App\Models\Collections\CommentCollection
     */
    // public function getByAuthorAttribute(): bool {}

    /**
     * Determine that the comment was created by a registered user.
     *
     * @return boolean
     */
    public function getByUserAttribute(): bool
    {
        return is_int($this->user_id) && $this->user_id > 0;
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

    /**
     * Determine that the comment has been approved for public display.
     *
     * @param  boolean  $value
     * @return boolean
     */
    public function getIsApprovedAttribute(bool $value): bool
    {
        return $this->setting->moderate ? $value : true;
    }

    /**
     * Get the comment URL.
     *
     * @return string|null
     */
    public function getUrlAttribute(): ?string
    {
        if ($parentUrl = $this->commentable->url) {
            return "{$parentUrl}#comment-{$this->id}";
        }

        return null;
    }
}
