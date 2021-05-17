<?php

namespace App\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Response as AccessResponse;
use Illuminate\Contracts\Auth\Access\Gate as AccessGate;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;

abstract class ActionAbstract
{
    protected Container $container;

    protected ?AccessGate $accessGate;
    protected ?Translator $translator;
    protected ?ValidationFactory $validationFactory;

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
     * @param  Container  $container
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
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
        return $this->accessGate()
            ->authorize($ability, $arguments);
    }

    /**
     * Get the Gate implementation.
     *
     * @return AccessGate
     */
    protected function accessGate(): AccessGate
    {
        return $this->accessGate
            ?? $this->accessGate = $this->container->make(
                AccessGate::class);
    }

    /**
     * Get the translation for a given key.
     *
     * @param  string  $key
     * @param  array  $replace
     * @return string
     */
    public function translate(string $key, array $replace = []): string
    {
        return $this->translator()
            ->get($key, $replace);
    }

    /**
     * Get the Translator implementation.
     *
     * @return Translator
     */
    protected function translator(): Translator
    {
        return $this->translator
            ?? $this->translator = $this->container->make(
                Translator::class);
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
     * Get the ValidationFactory implementation.
     *
     * @return ValidationFactory
     */
    protected function validationFactory(): ValidationFactory
    {
        return $this->validationFactory
            ?? $this->validationFactory = $this->container->make(
                ValidationFactory::class);
    }

    /**
     * Create a new Validator instance.
     *
     * @param  array  $input
     * @return Validator
     */
    protected function createValidator(array $input): Validator
    {
        return $this->validationFactory()
            ->make(
                $input,
                $this->rules(),
                $this->messages(),
                $this->attributes()
            )
            ->stopOnFirstFailure(
                $this->stopOnFirstFailure
            );
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
}
