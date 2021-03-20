<?php

namespace App\Http\Middleware;

use App\Contracts\JsonApiContract;
use App\Exceptions\JsonApiException;
use App\Support\JsonApi;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JsonApiHeaderMiddleware
{
    /** @var JsonApi */
    protected $jsonApi;

    /** @var Request */
    protected $request;

    public function __construct(JsonApiContract $jsonApi)
    {
        $this->jsonApi = $jsonApi;
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
        $this->jsonApi->setRequest(
            $this->request = $request
        );

        $this->ensureIsApiRoute()
            ->ensureIsSupportedHeaders()
            ->resourceHeaderIsDefined();

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

    protected function resourceHeaderIsDefined(): self
    {
        if (array_key_exists(
            $this->request->header($this->jsonApi::HEADER_RESOURCE),
            $this->jsonApi::RESORCE_TO_MODEL_MAP
        )) {
            return $this;
        }

        throw JsonApiException::make(JsonResponse::HTTP_NOT_ACCEPTABLE);
    }

    protected function isApiRoute(): bool
    {
        return $this->jsonApi->isApiUrl();
    }

    protected function isSupportedContentType(): bool
    {
        return $this->request->header('CONTENT_TYPE') === $this->jsonApi::HEADER_CONTENT_TYPE;
    }

    protected function isSupportedAccept(): bool
    {
        return $this->request->header('ACCEPT') === $this->jsonApi::HEADER_ACCEPT;
    }
}
