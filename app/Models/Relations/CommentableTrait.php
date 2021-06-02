<?php

namespace App\Models\Relations;

use App\Models\Collections\CommentCollection;
use App\Models\Comment;
use App\Models\Contracts\CommentableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

/**
 * @property-read EloquentCollection|Comment[] $comments Get all of the comments for the current model.
 * @property-read ?string $comment_store_url Get a non-existing attribute `$commentable->comment_store_url` for html-form.
 */
trait CommentableTrait
{
    /**
     * Boot the Commentable trait for a model.
     *
     * @return void
     */
    public static function bootCommentableTrait(): void
    {
        static::deleting(function (CommentableContract $commentable) {
            $commentable->comments()->get(['id'])->each->delete();
        });
    }

    /**
     * Get all of the comments for the current model.
     *
     * @return MorphMany
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(
            Comment::class,      // $related
            'commentable',       // $name
            'commentable_type',  // $type
            'commentable_id',    // $id
            $this->getKeyName(), // $localKey
        );
    }

    /**
     * Get a list comments with user relation, if need
     *
     * @param  boolean  $nested
     * @return CommentCollection
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
     * @return string|null
     */
    public function getCommentStoreUrlAttribute(): ?string
    {
        if (! $this->exists) {
            return null;
        }

        return route("{$this->table}.comments.store", $this);
    }
}
