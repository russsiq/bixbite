<?php

namespace App\Actions\User;

use App\Contracts\Actions\User\DeletesUsers;
use App\Models\User;

class DeleteUserAction extends UserActionAbstract implements DeletesUsers
{
    /**
     * Delete the given user.
     *
     * @param  User  $user
     * @return void
     */
    public function delete(User $user): void
    {
        $this->authorize(
            'delete', $this->user = $user->fresh()
        );

        $user->delete();
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            //
        ];
    }
}
