<?php

namespace BBCMS\Policies;

use BBCMS\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->canDo('admin.users.index');
    }

    public function view(User $user, User $model)
    {
        return $user->canDo('admin.users.view');
    }

    public function create(User $user)
    {
        return $user->canDo('admin.users.create');
    }

    public function update(User $user, User $model)
    {
        return $user->canDo('admin.users.update');
    }

    public function otherUpdate(User $user)
    {
        return $user->canDo('admin.users.other_update');
    }

    public function delete(User $user, User $model)
    {
        // only owner site or profile
        return 'owner' == $user->role or $model->id === $user->id;
    }
}
