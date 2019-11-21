<?php

namespace BBCMS\Http\Resources;

use BBCMS\Models\Privilege;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'role' => $this->role,
            'info' => $this->info,
            'where_from' => $this->where_from,
            'is_online' => $this->is_online,
            'last_ip' => $this->last_ip,

            // Конвертируем временные метки в строки.
            // Можно и так ->toDateTimeString().
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'logined_at' => (string) $this->logined_at,
        ];
    }

    public function with($request)
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
