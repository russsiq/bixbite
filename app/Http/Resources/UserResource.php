<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Privilege;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * The default model associated with the resource.
     *
     * @var string
     */
    public $model = User::class;

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
        $roles = array_reverse(array_values(
            Privilege::getModel()->roles()
        ));

        return [
            'meta' => [
                'roles' => $roles,
            ],
        ];
    }
}
