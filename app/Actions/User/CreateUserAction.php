<?php

namespace App\Actions\User;

use App\Contracts\Actions\User\CreatesUsers;
use App\Models\User;
use Illuminate\Validation\Rule;

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
        $validated = $this->createValidator(
            $input,
            $this->rules()
        )->validate();

        $this->user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $this->makeHash($validated['password']),
        ]);

        return $this->user;
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
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'terms' => 'accepted',
        ];
    }
}
