<?php

namespace App\Http\Middleware;

use App\Exceptions\JsonApiException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class JsonApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var JsonResponse */
        $response = $next($request);

        $this->wrapException($response->exception);

        return $response;
    }

    protected function wrapException(mixed $exception): void
    {
        if ($exception instanceof ValidationException) {
            throw JsonApiException::makeFromValidator(
                $exception->validator
            );
        }
    }
}
