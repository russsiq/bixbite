<?php

namespace App\Http\Resources;

use App\Models\XField;
use Illuminate\Http\Resources\Json\ResourceCollection;

class XFieldCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = XField::class;

    /**
     * Transform the resource into a JSON array.
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
        $model = $this->collects::getModel();

        return [
            'meta' => [
                'orderableColumns' => $model->orderableColumns(),
                'allowedFilters' => $model->allowedFilters(),
            ],
        ];
    }
}
