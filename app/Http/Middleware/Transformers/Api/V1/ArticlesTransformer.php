<?php

namespace App\Http\Middleware\Transformers\Api\V1;

// Сторонние зависимости.
use App\Support\Contracts\ResourceRequestTransformer;
use Illuminate\Http\Request;

class ArticlesTransformer implements ResourceRequestTransformer
{
    /**
     * [protected description]
     * @var Request
     */
    protected $request;

    /**
     * [__construct description]
     * @param  Request  $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * [default description]
     * @return array
     */
    public function default(): array
    {
        return $this->request->all();
    }

    /**
     * [store description]
     * @return array
     */
    public function store(): array
    {
        $inputs = [];

        return $inputs;
    }

    /**
     * [update description]
     * @return array
     */
    public function update(): array
    {
        $inputs = [];

        $inputs['title'] = strtoupper($this->request->input('title'));

        return $inputs;
    }

    /**
     * [massUpdate description]
     * @return array
     */
    public function massUpdate(): array
    {
        $inputs = [];

        return $inputs;
    }
}
