<?php

namespace App\Policies;

use App\Models\User;
use App\Models\XField;
use Illuminate\Auth\Access\HandlesAuthorization;

class XFieldPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\XField  $xField
     * @return mixed
     */
    public function view(User $user, XField $xField)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\XField  $xField
     * @return mixed
     */
    public function update(User $user, XField $xField)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\XField  $xField
     * @return mixed
     */
    public function delete(User $user, XField $xField)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\XField  $xField
     * @return mixed
     */
    public function restore(User $user, XField $xField)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\XField  $xField
     * @return mixed
     */
    public function forceDelete(User $user, XField $xField)
    {
        return false;
    }
}
