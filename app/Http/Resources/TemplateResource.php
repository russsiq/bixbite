<?php

namespace App\Http\Resources;

// Сторонние зависимости.
use App\Models\Template;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
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
}
