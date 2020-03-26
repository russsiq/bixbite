<?php

namespace App\Models\Observers;

// Сторонние зависимости.
use App\Models\Comment;

/**
 * Наблюдатель модели `Comment`.
 */
class CommentObserver extends BaseObserver
{
    /**
     * Обработать событие `deleting` модели.
     * @param  Comment  $comment
     * @return void
     */
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
