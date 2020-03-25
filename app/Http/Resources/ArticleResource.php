<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    // /**
    //  * The "data" wrapper that should be applied.
    //  *
    //  * @var string
    //  */
    // public static $wrap = null;

    /**
     * Преобразовать ресурс в массив.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // ->getRawOriginal не проходит проверку cast, hidden.
        // >getDirty неизвесто
        // >getOriginal проходит проверку cast, hidden.
        return array_merge($this->resource->getOriginal(), [
            'url' => $this->url,
            'categories' => new CategoryResource($this->whenLoaded('categories')),
            'comments' => new CommentResource($this->whenLoaded('comments')),
            'files' => new FileResource($this->whenLoaded('files')),
            'tags' => new TagResource($this->whenLoaded('tags')),
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

    public function with($request)
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
