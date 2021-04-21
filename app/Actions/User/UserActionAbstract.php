<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Response as AccessResponse;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Laravel\Fortify\Rules\Password;

abstract class UserActionAbstract
{
    /** @var Gate */
    protected $gate;

    /** @var Hasher */
    protected $hasher;

    /** @var Translator */
    protected $translator;

    /** @var User|null */
    protected $user;

    /** @var ValidationFactory */
    protected $validationFactory;

    /**
     * The key to be used for the view error bag.
     *
     * @var string|null
     */
    protected $validationErrorBag;

    /**
     * Indicates whether validation should stop after the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = false;

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    abstract protected function rules(): array;

    /**
     * Create a new Action instance.
     *
     * @param Gate  $gate
     * @param Hasher  $hasher
     * @param Translator  $translator
     * @param ValidationFactory  $validationFactory
     */
    public function __construct(
        Gate $gate,
        Hasher $hasher,
        Translator $translator,
        ValidationFactory $validationFactory
    ) {
        $this->gate = $gate;
        $this->hasher = $hasher;
        $this->translator = $translator;
        $this->validationFactory = $validationFactory;
    }

    /**
     * Authorize a given action for the current user.
     *
     * @param  string  $ability
     * @param  mixed  $arguments
     * @return AccessResponse
     *
     * @throws AuthorizationException
     */
    protected function authorize(string $ability, mixed $arguments): AccessResponse
    {
        return $this->gate->authorize($ability, $arguments);
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    protected function messages(): array
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    protected function attributes(): array
    {
        return [];
    }

    /**
     * Create a new Validator instance.
     *
     * @param  array  $input
     * @return Validator
     */
    protected function createValidator(array $input): Validator
    {
        return $this->validationFactory->make(
            $input,
            $this->rules(),
            $this->messages(),
            $this->attributes()
        )->stopOnFirstFailure($this->stopOnFirstFailure);
    }

    /**
     * Run the validator's rules against its data.
     *
     * @param  array  $input
     * @return array
     *
     * @throws ValidationException
     */
    protected function validate(array $input): array
    {
        $validator = $this->createValidator($input);

        return $this->validationErrorBag
            ? $validator->validateWithBag($this->validationErrorBag)
            : $validator->validate();
    }

    /**
     * Hash the given value.
     *
     * @param  string  $value
     * @return string
     */
    protected function makeHash(string $value): string
    {
        return $this->hasher->make($value);
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
        return $this->hasher->check($value, $hashedValue, $options);
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
