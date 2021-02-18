<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword extends UserActionAbstract implements UpdatesUserPasswords
{
    /**
     * Validate and update the user's password.
     *
     * @param  User  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input): void
    {
        $validated = $this->createValidator(
            $input,
            $this->rules($user)
        )->validateWithBag('updatePassword');

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
            'current_password' => [
                'bail',
                'required',
                'string',
                function ($attribute, $value, $message) use ($user) {
                    ! $this->checkHash($value, $user->password) && $message(
                        trans('The provided password does not match your current password.')
                    );
                },
            ],
            'password' => $this->passwordRules(),
        ];
    }
}
