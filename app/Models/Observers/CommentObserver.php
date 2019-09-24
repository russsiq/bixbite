<?php

namespace BBCMS\Models\Observers;

use BBCMS\Models\Comment;

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
