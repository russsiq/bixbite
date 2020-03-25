<?php

namespace App\Http\Resources;

// Сторонние зависимости.
use App\Models\User;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
                'orderableColumns' => User::getModel()->orderableColumns(),
                'allowedFilters' => User::getModel()->allowedFilters(),

            ],

        ];
    }
}
