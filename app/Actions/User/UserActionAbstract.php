<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Hashing\HashManager;
use Laravel\Fortify\Rules\Password;

abstract class UserActionAbstract
{
    /** @var HashManager */
    protected $hashManager;

    /** @var Translator */
    protected $translator;

    /** @var ValidationFactory */
    protected $validationFactory;

    /**
     * Create a new Action instance.
     *
     * @param HashManager  $hashManager
     * @param Translator  $translator
     * @param ValidationFactory  $validationFactory
     */
    public function __construct(
        HashManager $hashManager,
        Translator $translator,
        ValidationFactory $validationFactory
    ) {
        $this->hashManager = $hashManager;
        $this->translator = $translator;
        $this->validationFactory = $validationFactory;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param  User|null  $user
     * @return array
     */
    abstract protected function rules(?User $user): array;

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
        return $this->hashManager->make($value);
    }

    /**
     * Check the given plain value against a hash.
     *
     * @param  string  $value
     * @param  string  $hashedValue
     * @param  array  $options
     * @return bool
     */
    public function checkHash(
        string $value,
        string $hashedValue,
        array $options = []
    ): bool {
        return $this->hashManager->check($value, $hashedValue, $options);
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
