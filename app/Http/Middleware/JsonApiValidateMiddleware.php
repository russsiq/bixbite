<?php

namespace App\Http\Middleware;

use App\Contracts\JsonApiContract;
use Closure;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

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
        $this->setRequest($request);

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
}
