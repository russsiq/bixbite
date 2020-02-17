<?php

namespace BBCMS\Policies;

use BBCMS\Models\User;
use BBCMS\Models\Category;

class CategoryPolicy extends BasePolicy
{
    /**
     * Просмотр списка категорий.
     * Разрешено всем пользователям.
     * @param  User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function massUpdate(User $user): bool
    {
        return $this->update($user);

        return $user->canDo('categories.massUpdate');
    }

    // public function delete(User $user, Category $category) {
    //     return $user->canDo('categories.delete');
    // }
}
