<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $validated = $this->validator($input)->validate();

        return User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            // Only 'user'.
            'role' => 'user',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data  Request data.
     * @return Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(User::class),
            ],

            'email' => [
                'required',
                'string',
                'max:255',
                'email',
                Rule::unique(User::class),
            ],

            'password' => $this->passwordRules(),

            'registration_rules' => [
                'required',
                'boolean',
                'accepted',
            ],
        ]);
    }
}
