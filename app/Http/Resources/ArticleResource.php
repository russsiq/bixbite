<?php

namespace App\Http\Resources;

// Сторонние зависимости.
use App\Models\Article;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Преобразовать ресурс в массив.
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        // ->getRawOriginal не проходит проверку cast, hidden.
        // ->getDirty неизвесто
        // >getOriginal проходит проверку cast, hidden.
        return array_merge($this->resource->getOriginal(), [
            'content' => $this->resource->getRawOriginal('content'),
            'url' => $this->url,
            'categories' => new CategoryCollection($this->whenLoaded('categories')),
            'comments' => new CommentCollection($this->whenLoaded('comments')),
            'files' => new FileCollection($this->whenLoaded('files')),
            'tags' => new TagCollection($this->whenLoaded('tags')),
            'user' => new UserResource($this->whenLoaded('user')),

        ]);

        return parent::toArray($request);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),

        ];
    }

    /**
     * Получить дополнительные данные, которые
     * должны быть возвращены с массивом ресурса.
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

                'x_fields' => $this->x_fields,

            ],

        ];
    }
}
