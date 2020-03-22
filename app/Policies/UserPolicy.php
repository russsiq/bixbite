<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends BasePolicy
{
    public function view(User $user, User $model)
    {
        return $user->hasRole('owner') or $user->id === $model->id;
    }

    public function update(User $user, User $model)
    {
        return $user->hasRole('owner') or $user->id === $model->id;
    }

    public function delete(User $user, User $model)
    {
        return $user->hasRole('owner') or $user->id === $model->id;
    }
}
