<?php

namespace App\Actions\User;

use App\Models\User;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPasswordAction extends UserActionAbstract implements UpdatesUserPasswords
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
        $this->user = $user->fresh();

        $validated = $this->createValidator(
            $input,
            $this->rules()
        )->validateWithBag('updatePassword');

        $user->forceFill([
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
            'current_password' => [
                'bail',
                'required',
                'string',
                function ($attribute, $value, $message) {
                    ! $this->checkHash($value, $this->user->password) && $message(
                        $this->translator->get(
                            'The provided password does not match your current password.'
                        )
                    );
                },
            ],
            'password' => $this->passwordRules(),
        ];
    }
}
