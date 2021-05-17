<?php

namespace App\Contracts\Actions\Comment;

use App\Models\Comment;

interface UpdatesComment
{
    /**
     * Validate and update the given comment.
     *
     * @param  Comment  $comment
     * @param  array  $input
     * @return Comment
     */
    public function update(Comment $comment, array $input): Comment;
}
