<?php

namespace App\Http\Resources;

use App\Models\XField;
use Illuminate\Http\Resources\Json\JsonResource;

class XFieldResource extends JsonResource
{
    /**
     * The default model associated with the resource.
     *
     * @var string
     */
    public $model = XField::class;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
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
                'extensibles' => $this->resource::extensibles(),
                'fieldTypes' => $this->resource::fieldTypes(),
            ],
        ];
    }
}
