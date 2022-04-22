<?php

namespace App\Http\Resources;

use App\Http\Resources\XFieldCollection;
use App\Models\Article;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * The default model associated with the resource.
     *
     * @var string
     */
    public $model = Article::class;

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
                    'articles' => $this->resource->settings->pluck('value', 'name')->toArray(),
                ],

                'x_fields' => $this->resource->x_fields->toArray(),
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
                new AttachmentCollection($this->resource->getRelation('attachments'))
            ),
            'categories' => $this->whenLoaded('categories', fn () =>
                new CategoryCollection($this->resource->getRelation('categories'))
            ),
            'comments' => $this->whenLoaded('comments', fn () =>
                new CommentCollection($this->resource->getRelation('comments'))
            ),
            'tags' => $this->whenLoaded('tags', fn () =>
                new TagCollection($this->resource->getRelation('tags'))
            ),
            'user' => $this->whenLoaded('user', fn () =>
                new UserResource($this->resource->getRelation('user'))
            ),
        ];
    }
}
