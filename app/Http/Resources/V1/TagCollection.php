<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

class TagCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        return [
            'data' => $data,

            'links' => [
                'self' => $this->when(
                    $this->resource instanceof AbstractPaginator,
                    function () {
                        return route('api.v1.tags.index', [
                            'page' => $this->resource->currentPage(),
                        ]);
                    }
                ),
            ],
        ];
    }
}
