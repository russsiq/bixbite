<?php

namespace App\Http\Resources;

// Сторонние зависимости.
use App\Models\Comment;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    /**
     * Преобразовать коллекцию ресурсов в массив.
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
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
                'orderableColumns' => Comment::getModel()->orderableColumns(),
                'allowedFilters' => Comment::getModel()->allowedFilters(),

            ],

        ];
    }
}
