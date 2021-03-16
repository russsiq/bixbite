<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $attributes = parent::toArray($request);

        return [
            'type' => 'categories',
            'id' => $attributes['id'],
            'attributes' => Arr::except($attributes, [
                'id',
            ]),
            'relationships' => [
                //
            ],
            'links' => [
                'self' => route('api.v1.categories.show', $this->resource),
            ],
        ];
    }
}
