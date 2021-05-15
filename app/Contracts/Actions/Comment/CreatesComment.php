<?php

namespace App\Contracts\Actions\Comment;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface CreatesComment
{
    /**
     * Validate and create a newly comment.
     *
     * @param  Model  $commentable
     * @param  array  $input
     * @param  User|null $user
     * @return Comment
     */
    public function create(Model $commentable, array $input, ?User $user): Comment;
}
