<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class CommentResource extends JsonResource
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
            'type' => 'comments',
            'id' => $attributes['id'],
            'attributes' => Arr::except($attributes, [
                'id',
            ]),
            'relationships' => [
                //
            ],
            'links' => [
                'self' => route('api.v1.comments.show', $this->resource),
            ],
        ];
    }
}