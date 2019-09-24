<?php

namespace BBCMS\Policies;

use BBCMS\Models\User;
use BBCMS\Models\Note;

class NotePolicy extends BasePolicy
{
    public function index(User $user)
    {
        return true;
    }

    public function view(User $user, Note $note)
    {
        return $user->id === $note->user_id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Note $note)
    {
        return $user->id === $note->user_id;
    }

    public function delete(User $user, Note $note)
    {
        return $user->id === $note->user_id;
    }
}
