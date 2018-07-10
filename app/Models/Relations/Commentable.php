<?php

namespace BBCMS\Models\Relations;

use BBCMS\Models\Comment;

trait Commentable
{
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable', 'commentable_type', 'commentable_id', 'id');
    }

    /**
     * Get a list comments with user relation, if need
     *
     * @param boolean $nested
     * @return mixed Collection
     */
    public function getComments(bool $nested = false)
    {
        $comments = $this->comments()->get();
        
        // If at least one comment is left by a registered user
        if ($comments->firstWhere('user_id', '>', 'null')) {
            $comments->load(['user:users.id,users.name,users.email,users.avatar']);
        }

        return $comments->treated($nested, $this->getAttributes());
    }

    /**
     * Get a non-existing attribute $entity->comment_store_action for html-form.
     *
     * @return string
     */
    public function getCommentStoreActionAttribute()
    {
        return route('comments.store', [$this->getMorphClass(), $this->id]);
    }
}
