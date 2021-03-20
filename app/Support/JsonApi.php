<?php

namespace App\Support;

use App\Contracts\JsonApiContract;
use Illuminate\Http\Request;

class JsonApi implements JsonApiContract
{
    /** @var Request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function isApiUrl(): bool
    {
        return $this->request->is(self::API_URL.'/*');
    }

    public function resourceName(): string
    {
        return $this->request->header(self::HEADER_RESOURCE);
    }

    public function resourceModelName(): string
    {
        return self::RESORCE_TO_MODEL_MAP[
            $this->resourceName()
        ];
    }

    public function setRequest(Request $request): static
    {
        $this->request = $request;

        return $this;
    }
}
