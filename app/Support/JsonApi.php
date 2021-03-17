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
}
