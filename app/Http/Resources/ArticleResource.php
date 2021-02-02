<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ArticleResource extends JsonResource
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
            'type' => 'articles',
            'id' => $attributes['id'],
            'attributes' => Arr::except($attributes, 'id'),
            'relationships' => [

            ],
        ];
    }

    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'links' => [
                'self' => route('api.articles.show', $this->resource),
            ],
        ];
    }
}
