<?php

namespace App\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

class CommentCollection extends Collection
{
    public function treated(bool $nested = false, array $related = [])
    {
        $collection = $this;

        $collection->transform(function ($comment) use ($collection, $nested, $related) {
            // Treating info about comment to make css .by_author.
            if (is_int($comment->user_id) and isset($related['user_id'])) {
                $comment->by_author = intval($related['user_id']) === $comment->user_id;
            } else {
                $comment->by_author = false;
            }

            // Formatting of a comment tree, if this need.
            if ($nested and $collection->firstWhere('parent_id', $comment->id)) {
                $comment->children = $collection->where('parent_id', $comment->id);
            } else {
                $comment->children = [];
            }

            return $comment;
        });

        // Finish stage formatting of a comment tree, if this need.
        if ($nested) {
            $collection = $collection->reject(function ($comment) {
                return $comment->parent_id !== null and $comment->parent_id !== 0;
            });
        }

        return $collection;
    }
}
