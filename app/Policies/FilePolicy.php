<?php

namespace App\Policies;

use App\Models\User;
use App\Models\File;

class FilePolicy extends BasePolicy
{
    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, File $file)
    {
        return true;
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, File $file)
    {
        return $user->hasRole('owner') or $user->id === $file->user_id;
    }

    public function delete(User $user, File $file)
    {
        return $user->hasRole('owner') or $user->id === $file->user_id;
    }
}
