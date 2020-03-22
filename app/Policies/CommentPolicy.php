<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;

class CommentPolicy extends BasePolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Comment $comment)
    {
        return $user->hasRole('owner') or $user->id === $comment->user_id;
    }

    public function create(?User $user)
    {
        if ($user instanceof User) {
            return true;
        }

        // Check if unregistered user are allowed to commenting.
        return ! setting('comments.regonly');
    }

    public function update(User $user, Comment $comment)
    {
        return $user->hasRole('owner') or $user->id === $comment->user_id;
    }

    public function massUpdate(User $user)
    {
        return $user->hasRole('owner') or $user->id === $comment->user_id;
    }

    public function delete(User $user, Comment $comment)
    {
        return $user->hasRole('owner') or $user->id === $comment->user_id;
    }
}
