<?php

namespace BBCMS\Policies;

use BBCMS\Models\User;
use BBCMS\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return true;
    }

    public function view(User $user, Comment $comment)
    {
        return 'owner' == $user->role or $user->id === $comment->user_id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Comment $comment)
    {
        return 'owner' == $user->role or $user->id === $comment->user_id;
    }

    public function otherUpdate(User $user)
    {
        return $user->canDo('admin.comments.other_update');
    }

    public function delete(User $user, Comment $comment)
    {
        return 'owner' == $user->role or $user->id === $comment->user_id;
    }
}