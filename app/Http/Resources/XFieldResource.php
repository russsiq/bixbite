<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class XFieldResource extends JsonResource
{
    /**
     * Преобразовать ресурс в массив.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function with($request)
    {
        return [
            'meta' => [
                'extensibles' => $this->resource::extensibles(),
                'fieldTypes' => $this->resource::fieldTypes(),
            ],
        ];
    }
}
