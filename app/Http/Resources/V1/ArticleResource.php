<?php

namespace App\Http\Resources\V1;

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
            'attributes' => Arr::except($attributes, [
                'id', 'atachments', 'categories', 'tags', 'user',
            ]),
            'relationships' => [
                'atachments' => $this->whenLoaded('atachments', function () use ($request) {
                    return AtachmentCollection::make($this->atachments)
                        ->toArray($request);
                }),
                'categories' => $this->whenLoaded('categories', function () use ($request) {
                    return CategoryCollection::make($this->categories)
                        ->toArray($request);
                }),
                'tags' => $this->whenLoaded('tags', function () use ($request) {
                    return TagCollection::make($this->tags)
                        ->toArray($request);
                }),
                'user' => $this->whenLoaded('user', function () use ($request) {
                    return UserResource::make($this->user)
                        ->toArray($request);
                }),
            ],
            'links' => [
                'self' => route('api.v1.articles.show', $this->resource),
            ],
        ];
    }
}
