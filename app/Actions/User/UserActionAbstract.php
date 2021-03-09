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
     * Create a new Validator instance.
     *
     * @param  array  $input
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return Validator
     */
    protected function createValidator(
        array $input,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ): Validator {
        return $this->validationFactory->make(
            $input,
            $rules,
            $messages,
            $customAttributes
        );
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
