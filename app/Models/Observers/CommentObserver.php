<?php

namespace App\Models\Observers;

use App\Models\Comment;

class CommentObserver
{
    public function deleting(Comment $comment)
    {
        $comment->where('parent_id', $comment->id)
            ->get([
                'id',
            ])
            ->each
            ->delete();
    }
}
