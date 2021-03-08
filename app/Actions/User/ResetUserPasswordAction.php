<?php

namespace App\Actions\User;

use App\Models\User;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPasswordAction extends UserActionAbstract implements ResetsUserPasswords
{
    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  User  $user
     * @param  array  $input
     * @return void
     */
    public function reset($user, array $input): void
    {
        $validated = $this->createValidator(
            $input,
            $this->rules($user)
        )->validate();

        $user->forceFill([
            'password' => $this->makeHash($validated['password']),
        ])->save();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param  User|null  $user
     * @return array
     */
    protected function rules(?User $user): array
    {
        return [
            'password' => $this->passwordRules(),
        ];
    }
}
