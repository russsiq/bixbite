<?php

namespace App\Http\Resources;

// Сторонние зависимости.
use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Преобразовать ресурс в массив.
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
                'template_list' => select_dir('custom_views'),

                'x_fields' => $this->x_fields,

            ],

        ];
    }
}
