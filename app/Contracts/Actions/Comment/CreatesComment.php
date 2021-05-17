<?php

namespace App\Contracts\Actions\Comment;

use App\Models\Comment;
use App\Models\Contracts\CommentableContract;

interface CreatesComment
{
    /**
     * Validate and create a newly comment.
     *
     * @param  CommentableContract  $commentable
     * @param  array  $input
     * @return Comment
     */
    public function create(CommentableContract $commentable, array $input): Comment;
}
