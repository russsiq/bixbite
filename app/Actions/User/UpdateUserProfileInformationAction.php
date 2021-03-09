<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformationAction extends UserActionAbstract implements UpdatesUserProfileInformation
{
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

        $validated = $this->createValidator(
            $input,
            $this->rules()
        )->validateWithBag('updateProfileInformation');

        if ($validated['email'] !== $this->user->email &&
            $this->user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($validated);
        } else {
            $this->user->forceFill([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser(array $input): void
    {
        $this->user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $this->user->sendEmailVerificationNotification();
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
