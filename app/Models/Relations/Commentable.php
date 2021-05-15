<?php

namespace App\Models\Relations;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

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
        $comments = $this->comments()
            ->when(setting('comments.moderate', true), function($query) {
                $query->where('is_approved', true)
                    ->when(Auth::check(), function($query) {
                        $query->orWhere('user_id', Auth::id());
                    });
            })
            ->get();

        // If at least one comment is left by a registered user
        if ($comments->firstWhere('user_id', '<>', null)) {
            $comments->load([
                'user:users.id,users.name,users.email,users.avatar',
            ]);
        }

        return $comments->treated($nested, $this->getAttributes());
    }

    /**
     * Get a non-existing attribute $commentable->comment_store_url for html-form.
     *
     * @return string
     */
    public function getCommentStoreUrlAttribute()
    {
        return route(static::TABLE.'.comments.store', $this);
    }
}
