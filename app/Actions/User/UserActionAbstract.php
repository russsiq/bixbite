<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;
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

    /** @var string|null */
    protected $validationErrorBag;

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
     * Get the validation rules that apply to the request.
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
        );
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
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            'confirmed',
            new Password,
        ];
    }
}
