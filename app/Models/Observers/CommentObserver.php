<?php

namespace App\Models\Observers;

use App\Models\Comment;

class CommentObserver extends BaseObserver
{
    public function deleting(Comment $comment): void
    {
        $comment->where('parent_id', $comment->id)
            ->get([
                'id',
                'parent_id',
            ])
            ->each->delete();
    }
}
