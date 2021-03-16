<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class AtachmentResource extends JsonResource
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
            'type' => 'atachments',
            'id' => $attributes['id'],
            'attributes' => Arr::except($attributes, [
                'id', 'user',
            ]),
            'relationships' => [
                'user' => $this->whenLoaded('user', function () use ($request) {
                    return UserResource::make($this->user)
                        ->toArray($request);
                }),
            ],
            'links' => [
                'self' => route('api.v1.atachments.show', $this->resource),
            ],
        ];
    }
}
