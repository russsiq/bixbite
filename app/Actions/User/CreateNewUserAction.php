<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUserAction extends UserActionAbstract implements CreatesNewUsers
{
    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return User
     */
    public function create(array $input): User
    {
        $validated = $this->createValidator(
            $input,
            $this->rules(null)
        )->validate();

        return User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $this->makeHash($validated['password']),
        ]);
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
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'terms' => [
                'accepted',
            ],
        ];
    }
}
