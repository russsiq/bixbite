<?php

namespace App\Actions\User;

use App\Contracts\Actions\User\UpdatesUserProfileInformation;
use App\Models\User;

class UpdateUserProfileInformationAction extends UserActionAbstract implements UpdatesUserProfileInformation
{
    /** @var string|null */
    protected $validationErrorBag = 'updateProfileInformation';

    /**
     * Validate and update the given user's profile information.
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
            'name' => $validated['name'],
            'email' => $validated['email'],
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
            $this->nameRules(),
            $this->emailRules(),
        );
    }
}
