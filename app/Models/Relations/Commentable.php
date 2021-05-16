<?php

namespace App\Models\Relations;

use App\Models\Collections\CommentCollection;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait Commentable
{
    /**
     * Get all comments for the current model.
     *
     * @return MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(
            Comment::class,
            'commentable',
            'commentable_type',
            'commentable_id',
            $this->getKeyName()
        );
    }

    /**
     * Get a list comments with user relation, if need
     *
     * @param  boolean  $nested
     * @return mixed  CommentCollection
     */
    public function getComments(bool $nested = false): CommentCollection
    {
        return $this->comments()
            ->where(function (Builder $query) {
                $query->when(
                    setting('comments.moderate', true),
                    function(Builder $query) {
                        $query->where('is_approved', true);
                        $query->when(
                            Auth::check(),
                            fn (Builder $query) => $query->orWhere('user_id', Auth::id())
                        );
                    }
                );
            })
            ->get()
            ->treated($nested, $this->getAttributes());
    }

    /**
     * Get a non-existing attribute `$commentable->comment_store_url` for html-form.
     *
     * @return string
     */
    public function getCommentStoreUrlAttribute(): string
    {
        return route("{$this->table}.comments.store", $this);
    }
}
