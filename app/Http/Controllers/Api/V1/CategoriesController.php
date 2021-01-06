<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Category\Store as StoreCategoryRequest;
use App\Http\Requests\Api\V1\Category\Update as UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoriesController extends ApiController
{
    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Отобразить весь список сущностей,
     * включая связанные сущности.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = Category::withCount([
            'articles',
        ])
            ->orderByRaw('ISNULL(`position`), `position` ASC')
            ->get();

        $collection = new CategoryCollection($categories);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Создать и сохранить сущность в хранилище.
     *
     * @param  StoreCategoryRequest  $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->all());

        $resource = new CategoryResource($category);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Отобразить сущность.
     *
     * @param  Category  $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        $category->loadCount([
            'articles',
        ])
            ->load([
                'files',
            ]);

        $resource = new CategoryResource($category);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     *
     * @param  UpdateCategoryRequest  $request
     * @param  Category  $category
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->all());

        $resource = new CategoryResource($category);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Удалить сущность из хранилища.
     *
     * @param  Category  $category
     * @return JsonResponse
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
