<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Tag;

use App\Http\Resources\TagResource;
use App\Http\Resources\TagCollection;

use App\Http\Requests\Api\V1\Tag\Store as StoreTagRequest;
use App\Http\Requests\Api\V1\Tag\Update as UpdateTagRequest;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TagsController extends ApiController
{
    protected $advancedAbilityMap = [

    ];

    protected $advancedMethodsWithoutModels = [

    ];

    public function __construct()
    {
        $this->authorizeResource(Tag::class, 'tag');
    }

    /**
     * Отобразить список тегов.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tags = Tag::withCount([
                'articles'
            ])
            ->get();

        $collection = new TagCollection($tags);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Сохранить вновь созданный тег в хранилище.
     * @param  StoreTagRequest  $request
     * @return JsonResponse
     */
    public function store(StoreTagRequest $request): JsonResponse
    {
        $tag = Tag::create($request->all());

        $resource = new TagResource($tag);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Отобразить сущность.
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
     * @param  UpdateTagRequest  $request
     * @param  Tag  $tag
     * @return JsonResponse
     */
    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        $tag->update($request->all());

        $resource = new TagResource($tag);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Удалить сущность из хранилища.
     * @param  Tag  $tag
     * @return JsonResponse
     */
    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
