<?php

namespace App\Contracts\Actions\Comment;

use App\Models\Comment;
use App\Models\Contracts\CommentableContract;
use App\Models\User;

interface CreatesComment
{
    /**
     * Validate and create a newly comment.
     *
     * @param  CommentableContract  $commentable
     * @param  array  $input
     * @param  User|null $user
     * @return Comment
     */
    public function create(CommentableContract $commentable, array $input, ?User $user): Comment;
}
