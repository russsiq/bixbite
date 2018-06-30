<?php

namespace BBCMS\Policies;

use BBCMS\Models\User;
use BBCMS\Models\File;
use Illuminate\Auth\Access\HandlesAuthorization;

class FilePolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return true;
    }

    public function view(User $user, File $file)
    {
        return $user->id === $file->user_id;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, File $file)
    {
        return $user->id === $file->user_id;
    }

    public function delete(User $user, File $file)
    {
        return $user->id === $file->user_id;
    }
}
