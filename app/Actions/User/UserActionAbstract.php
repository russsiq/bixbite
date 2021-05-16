<?php

namespace App\Actions\User;

use App\Actions\ActionAbstract;
use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Laravel\Fortify\Rules\Password;

abstract class UserActionAbstract extends ActionAbstract
{
    protected ?Hasher $hasher;

    protected ?User $user = null;

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * Get the Hasher implementation.
     *
     * @return Hasher
     */
    protected function hasher(): Hasher
    {
        return $this->hasher
            ?? $this->hasher = $this->container->make(
                Hasher::class);
    }

    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @return string
     */
    protected function makeHash(string $value): string
    {
        return $this->hasher()
            ->make($value);
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @param  array  $options
     * @return bool
     */
    protected function checkHash(
        string $value,
        string $hashedValue,
        array $options = []
    ): bool {
        return $this->hasher()
            ->check($value, $hashedValue, $options);
    }

    /**
     * Get the validation rules used to validate `name` field.
     *
     * @return array
     */
    protected function nameRules(): array
    {
        return [
            'name' => [
                'bail',
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `email` field.
     *
     * @return array
     */
    protected function emailRules(): array
    {
        return [
            'email' => [
                'bail',
                'required',
                'email',
                'max:255',
                with(
                    Rule::unique(User::TABLE, 'email'),
                    fn (Unique $unique) => $this->user instanceof User
                        ? $unique->ignore($this->user->id, 'id')
                        : $unique
                ),
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `password` field.
     *
     * @return array
     */
    protected function passwordRules(): array
    {
        return [
            'password' => [
                'bail',
                'required',
                'string',
                'confirmed',
                new Password,
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `current_password` field.
     *
     * @return array
     */
    protected function currentPasswordRules(): array
    {
        return [
            'current_password' => [
                'bail',
                'required',
                'string',
                'password',
            ],
        ];
    }

    /**
     * Get the validation rules used to validate `terms` field.
     *
     * @return array
     */
    protected function termsRules(): array
    {
        return [
            'terms' => 'accepted',
        ];
    }
}
