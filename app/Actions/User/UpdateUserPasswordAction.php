<?php

namespace App\Actions\User;

use App\Contracts\Actions\User\UpdatesUserPasswords;
use App\Models\User;

class UpdateUserPasswordAction extends UserActionAbstract implements UpdatesUserPasswords
{
    /** @var string|null */
    protected $validationErrorBag = 'updatePassword';

    /** @var bool */
    protected $stopOnFirstFailure = true;

    /**
     * Validate and update the user's password.
     *
     * @param  User  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input): void
    {
        $this->authorize(
            'update',
            $this->user = $user->fresh()
        );

        $validated = $this->validate($input);

        $this->user->forceFill([
            'password' => $this->makeHash($validated['password']),
        ])->save();
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            $this->currentPasswordRules(),
            $this->passwordRules(),
        );
    }
}
