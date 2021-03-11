<?php

namespace App\Actions\User;

use App\Contracts\Actions\User\CreatesUsers;
use App\Models\User;

class CreateUserAction extends UserActionAbstract implements CreatesUsers
{
    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return User
     */
    public function create(array $input): User
    {
        $validated = $this->validate($input);

        $this->user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $this->makeHash($validated['password']),
        ])->fresh();

        return $this->user;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        return array_merge(
            $this->nameRules(),
            $this->emailRules(),
            $this->passwordRules(),
            $this->termsRules(),
        );
    }
}
