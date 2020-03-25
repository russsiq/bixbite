<?php

namespace App\Http\Resources;

// Сторонние зависимости.
use App\Models\XField;
use Illuminate\Http\Resources\Json\JsonResource;

class XFieldResource extends JsonResource
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
                'extensibles' => XField::extensibles(),
                'fieldTypes' => XField::fieldTypes(),

            ],

        ];
    }
}
