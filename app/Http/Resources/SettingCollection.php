<?php

namespace App\Http\Resources;

// Сторонние зависимости.
use App\Models\Setting;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SettingCollection extends ResourceCollection
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
}
