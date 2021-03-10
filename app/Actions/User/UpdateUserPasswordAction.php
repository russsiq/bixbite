<?php

namespace App\Actions\User;

use App\Contracts\Actions\User\UpdatesUserPasswords;
use App\Models\User;

class UpdateUserPasswordAction extends UserActionAbstract implements UpdatesUserPasswords
{
    /** @var string|null */
    protected $validationErrorBag = 'updatePassword';

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
