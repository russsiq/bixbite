<?php

namespace App\Http\Resources;

use App\Models\Note;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * The resource instance.
     *
     * @var Note
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge($this->resource->attributesToArray(), [
            //
        ], $this->relationships($request));
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
            //
        ];
    }

    /**
     * Get the transformed relationships of the the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function relationships($request): array
    {
        return [
            'attachments' => $this->whenLoaded('attachments', fn () =>
                AttachmentCollection::make($this->resource->getRelation('attachments'))->toArray($request)
            ),
            'user' => $this->whenLoaded('user', fn () =>
                UserResource::make($this->resource->getRelation('user'))->toArray($request)
            ),
        ];
    }
}
