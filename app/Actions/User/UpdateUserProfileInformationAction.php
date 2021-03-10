<?php

namespace App\Actions\User;

use App\Contracts\Actions\User\UpdatesUserProfileInformation;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\Rule;

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
        ]);

        if ($this->mustVerifyEmail()) {
            $this->updateVerifiedUser();
        } else {
            $this->user->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @return void
     */
    protected function updateVerifiedUser(): void
    {
        $this->user->email_verified_at = null;

        $this->user->save();

        $this->user->sendEmailVerificationNotification();
    }

    /**
     * Determine if the user needs to verify their email.
     *
     * @return boolean
     */
    protected function mustVerifyEmail(): bool
    {
        return $this->user instanceof MustVerifyEmail
            && $this->user->isDirty('email');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user->id),
            ],
        ];
    }
}
