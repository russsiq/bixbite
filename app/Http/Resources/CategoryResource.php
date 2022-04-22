<?php

namespace App\Http\Resources;

use App\Http\Resources\XFieldCollection;
use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * The default model associated with the resource.
     *
     * @var string
     */
    public $model = Category::class;

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
                'template_list' => select_dir('custom_views'),

                'x_fields' => $this->resource->x_fields->toArray(),
            ],
        ];
    }
}
