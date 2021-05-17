<?php

namespace App\Contracts\Actions\Comment;

use App\Models\Comment;

interface DeletesComment
{
    /**
     * Delete the given comment.
     *
     * @param  Comment  $comment
     * @return int  Remote comment ID.
     */
    public function delete(Comment $comment): int;
}
