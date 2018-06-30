<?php

namespace BBCMS\Policies;

use BBCMS\Models\User;
use BBCMS\Models\Privilege;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrivilegePolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->canDo('admin.privileges.index');
    }

    public function view(User $user, Privilege $privilege)
    {
        return $user->canDo('admin.privileges.view');
    }

    public function create(User $user)
    {
        return $user->canDo('admin.privileges.create');
    }

    public function update(User $user, Privilege $privilege)
    {
        return $user->canDo('admin.privileges.update');
    }

    public function otherUpdate(User $user)
    {
        return $user->canDo('admin.privileges.other_update');
    }

    public function delete(User $user, Privilege $privilege)
    {
        return $user->canDo('admin.privileges.delete');
    }
}
