<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var Article
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge($this->resource->attributesToArray(), [
            //
        ], $this->relationships($request));
    }

    /**
     * Get any additional data that should be returned with the resource array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request): array
    {
        return [
            'meta' => [
                'setting' => [
                    'articles' => setting('articles'),
                ],

                'x_fields' => $this->resource->x_fields,
            ],
        ];
    }

    /**
     * Get the transformed relationships of the the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function relationships($request): array
    {
        return [
            'attachments' => $this->whenLoaded('attachments', fn () =>
                AttachmentCollection::make($this->resource->getRelation('attachments'))->toArray($request)
            ),
            'categories' => $this->whenLoaded('categories', fn () =>
                CategoryCollection::make($this->resource->getRelation('categories'))->toArray($request)
            ),
            'comments' => $this->whenLoaded('comments', fn () =>
                CommentCollection::make($this->resource->getRelation('comments'))->toArray($request)
            ),
            'tags' => $this->whenLoaded('tags', fn () =>
                TagCollection::make($this->resource->getRelation('tags'))->toArray($request)
            ),
            'user' => $this->whenLoaded('user', fn () =>
                UserResource::make($this->resource->getRelation('user'))->toArray($request)
            ),
        ];
    }
}
