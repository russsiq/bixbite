<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Query\Builder;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;

abstract class UserActionAbstract
{
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
     * Create a new Action instance.
     *
     * @param Hasher  $hasher
     * @param Translator  $translator
     * @param ValidationFactory  $validationFactory
     */
    public function __construct(
        Hasher $hasher,
        Translator $translator,
        ValidationFactory $validationFactory
    ) {
        $this->hasher = $hasher;
        $this->translator = $translator;
        $this->validationFactory = $validationFactory;
    }

    /**
     * Get the validation rules that apply to the action.
     *
     * @return array
     */
    abstract protected function rules(): array;

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
                'required',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')
                    ->where(function (Builder $query) {
                        if ($this->user instanceof User) {
                            $query->where('email', '<>', $this->user->email);
                        }
                    }),
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
                function ($attribute, $value, $message) {
                    ! $this->checkHash($value, $this->user->password) && $message(
                        $this->translator->get(
                            'The provided password does not match your current password.'
                        )
                    );
                },
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
