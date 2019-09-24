<?php

namespace BBCMS\Http\Controllers\Api\V1;

use BBCMS\Models\Category;

use BBCMS\Http\Resources\CategoryResource;
use BBCMS\Http\Resources\CategoryCollection;

use BBCMS\Http\Requests\Api\V1\Category\Store as StoreCategoryRequest;
use BBCMS\Http\Requests\Api\V1\Category\Update as UpdateCategoryRequest;

use Illuminate\Http\JsonResponse;

class CategoriesController extends ApiController
{
    protected $advancedAbilityMap = [
        // 'massUpdate' => 'massUpdate',
    ];

    protected $advancedMethodsWithoutModels = [
        // 'massUpdate',
    ];

    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Отобразить весь список сущностей,
     * включая связанные сущности.
     * @return JsonResponse
     */
    public function index()
    {
        $categories = Category::withCount([
                'articles'
            ])
            ->orderByRaw('ISNULL(`position`), `position` ASC')
            ->get();

        $collection = new CategoryCollection($categories);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Создать и сохранить сущность в хранилище.
     * @param  StoreCategoryRequest  $request
     * @return JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->all());

        $resource = new CategoryResource($category);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Отобразить сущность.
     * @param  Category  $category
     * @return JsonResponse
     */
    public function show(Category $category)
    {
        $category->articles_count = $category->articles()->count();
        $category->load([
            'files',
        ]);

        $resource = new CategoryResource($category);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     * @param  UpdateCategoryRequest  $request
     * @param  Category  $category
     * @return JsonResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());

        $resource = new CategoryResource($category);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Удалить сущность из хранилища.
     * @param  Category  $category
     * @return JsonResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
