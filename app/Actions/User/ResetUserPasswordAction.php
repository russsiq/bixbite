<?php

namespace App\Actions\User;

use App\Contracts\Actions\User\ResetsUserPasswords;
use App\Models\User;

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
        $this->user = $user->fresh();

        $validated = $this->validate($input);

        $this->user->forceFill([
            'password' => $this->makeHash($validated['password']),
        ])->save();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'password' => $this->passwordRules(),
        ];
    }
}
