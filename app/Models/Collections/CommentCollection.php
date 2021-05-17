<?php

namespace App\Models\Collections;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;

class CommentCollection extends Collection
{
    public function treated(bool $nested = false, array $related = [])
    {
        $collection = $this;

        $collection->transform(function ($comment) use ($collection, $nested, $related) {
            // Treating info about comment to make css .by_author.
            $comment->by_author = is_int($comment->user_id)
                && $comment->user_id === ($related['user_id'] ?? null);

            // Formatting of a comment tree, if this need.
            $comment->children = [];
            if ($nested && $collection->firstWhere('parent_id', $comment->id)) {
                $comment->children = $collection->where('parent_id', $comment->id);
            }

            return $comment;
        });

        // Finish stage formatting of a comment tree, if this need.
        if ($nested) {
            $collection = $collection->filter(
                fn (Comment $comment) => ! $comment->parent_id
            );
        }

        return $collection;
    }
}
