<?php

namespace BBCMS\Policies;

use BBCMS\Models\User;
use BBCMS\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    // view, create, update, and delete

    public function index(User $user)
    {
        return $user->canDo('admin.categories.index');
    }

    public function view(User $user, Category $category)
    {
        return $user->canDo('admin.categories.view');
    }

    public function create(User $user)
    {
        return $user->canDo('admin.categories.create');
    }

    public function update(User $user, Category $category)
    {
        return $user->canDo('admin.categories.update');
    }

    public function otherUpdate(User $user)
    {
        return $user->canDo('admin.categories.other_update');
    }

    public function delete(User $user, Category $category)
    {
        return $user->canDo('admin.categories.delete');
    }
}
