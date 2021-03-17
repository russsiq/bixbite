<?php

namespace App\Http\Middleware;

use App\Exceptions\JsonApiException;
use App\Support\JsonApi;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JsonApiHeaderMiddleware
{
    /** @var Request */
    protected $request;

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->request = $request;

        $this->ensureIsApiRoute()->ensureIsSupportedHeaders();

        return $next($request);
    }

    protected function ensureIsApiRoute(): self
    {
        if ($this->isApiRoute()) {
            return $this;
        }

        throw JsonApiException::make(JsonResponse::HTTP_NOT_ACCEPTABLE);
    }

    protected function ensureIsSupportedHeaders(): self
    {
        if ($this->isSupportedContentType() && $this->isSupportedAccept()) {
            return $this;
        }

        throw JsonApiException::make(JsonResponse::HTTP_UNSUPPORTED_MEDIA_TYPE);
    }

    protected function isApiRoute(): bool
    {
        return $this->request->is(JsonApi::API_URL.'/*');
    }

    protected function isSupportedContentType(): bool
    {
        return $this->request->header('CONTENT_TYPE') === JsonApi::HEADER_CONTENT_TYPE;
    }

    protected function isSupportedAccept(): bool
    {
        return $this->request->header('ACCEPT') === JsonApi::HEADER_ACCEPT;
    }
}
