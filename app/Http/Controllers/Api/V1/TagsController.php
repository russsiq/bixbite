<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Tag\Store as StoreTagRequest;
use App\Http\Requests\Api\V1\Tag\TagRequest;
use App\Http\Requests\Api\V1\Tag\Update as UpdateTagRequest;
use App\Http\Resources\TagCollection;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagsController extends ApiController
{
    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
        $this->authorizeResource(Tag::class, 'tag');
    }

    /**
     * Отобразить список тегов.
     *
     * @param TagRequest $request
     * @return JsonResponse
     */
    public function index(TagRequest $request): JsonResponse
    {
        $suggestion = $request->validated();

        $tags = Tag::withCount([
            'articles',
        ])
        ->when($suggestion['title'], function (Builder $query, $title) {
            $query->searchByKeyword($title);
        })
        ->get();

        $collection = new TagCollection($tags);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Сохранить вновь созданный тег в хранилище.
     *
     * @param  StoreTagRequest  $request
     * @return JsonResponse
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        $data = $request->validated();

        $tag = Tag::create($data);

        if (isset($data['taggable_type'])) {
            $tag->{$data['taggable_type']}()
                ->attach($data['taggable_id']);

            $tag->load($data['taggable_type']);
        }

        $resource = new TagResource($tag);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Отобразить сущность.
     *
     * @param  Tag  $tag
     * @return JsonResponse
     */
    public function show(Tag $tag): JsonResponse
    {
        $tag->articles_count = $tag->articles()->count();

        $resource = new TagResource($tag);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     *
     * @param  UpdateTagRequest  $request
     * @param  Tag  $tag
     * @return JsonResponse
     */
    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        $data = $request->validated();

        $tag->update($data);

        if (isset($data['taggable_type'])) {
            $tag->{$data['taggable_type']}()->attach($data['taggable_id']);
        }

        $resource = new TagResource($tag);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Удалить сущность из хранилища.
     *
     * @param  Tag  $tag
     * @return JsonResponse
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
