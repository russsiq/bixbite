<?php

namespace App\Contracts\Actions\User;

use App\Models\User;

interface DeletesUsers
{
    /**
     * Delete the given user.
     *
     * @param  User  $user
     * @return void
     */
    public function delete(User $user): void;
}
