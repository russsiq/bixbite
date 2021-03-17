<?php

namespace App\Exceptions;

use App\Contracts\JsonApiContract;
use App\Support\JsonApi;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        /** @var JsonApi */
        $jsonApi = $this->container->make(JsonApiContract::class);

        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (ValidationException $e, Request $request) use ($jsonApi) {
            if ($jsonApi->isApiUrl()) {
                throw JsonApiException::makeFromValidator($e->validator);
            }
        });
    }
}
