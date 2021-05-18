<?php

namespace App\Actions\User;

use App\Contracts\Actions\User\UpdatesUserProfileInformation;
use App\Models\User;
use App\Rules\Concerns\ExtraFieldsRules;

class UpdateUserProfileInformationAction extends UserActionAbstract implements UpdatesUserProfileInformation
{
    use ExtraFieldsRules;

    /** @var string */
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
        $this->authorize(
            'update',
            $this->user = $user->fresh()
        );

        $this->user->update(
            $this->validate($input)
        );
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            $this->extraFieldsRules(User::getModel()),
            $this->nameRules(),
            $this->emailRules(),
        );
    }
}
