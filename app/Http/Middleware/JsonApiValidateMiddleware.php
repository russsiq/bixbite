<?php

namespace App\Http\Middleware;

use App\Contracts\JsonApiContract;
use Closure;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JsonApiValidateMiddleware
{
    /** @var JsonApiContract */
    protected $jsonApi;

    /** @var Request */
    protected $request;

    /** @var Translator */
    protected $translator;

    /** @var ValidationFactory */
    protected $validationFactory;

    // /** @var bool */
    // protected $stopOnFirstFailure = true;

    /**
     * Create a new middleware instance.
     *
     * @param JsonApiContract  $jsonApi
     * @param Translator  $translator
     * @param ValidationFactory  $validationFactory
     */
    public function __construct(
        JsonApiContract $jsonApi,
        Translator $translator,
        ValidationFactory $validationFactory
    ) {
        $this->jsonApi = $jsonApi;
        $this->translator = $translator;
        $this->validationFactory = $validationFactory;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->setRequest($request)->validate();

        return $next($request);
    }

    /**
     * Set the Request instance.
     *
     * @param  Request  $request
     * @return $this
     */
    public function setRequest(Request $request): self
    {
        $this->jsonApi->setRequest(
            $this->request = $request
        );

        return $this;
    }

    /**
     * Run the validator's rules against its data.
     *
     * @return array
     *
     * @throws ValidationException
     */
    public function validate(): array
    {
        $validator = $this->createValidator()
            // ->stopOnFirstFailure($this->stopOnFirstFailure)
            ->after(function (ValidatorContract $validator) {
                //
            });

        if ($validator->fails()) {
            //
        }

        return $validator->validate();
    }

    /**
     * Create a new Validator instance.
     *
     * @return ValidatorContract
     */
    protected function createValidator(): ValidatorContract
    {
        return $this->validationFactory->make(
            $this->request->query(), $this->rules(),
            $this->messages(), $this->attributes()
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            //
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            //
        ];
    }
}
