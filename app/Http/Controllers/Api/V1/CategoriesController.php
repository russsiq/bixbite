<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Actions\Category\CreatesCategory;
use App\Contracts\Actions\Category\DeletesCategory;
use App\Contracts\Actions\Category\FetchesCategory;
use App\Contracts\Actions\Category\UpdatesCategory;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  FetchesCategory  $fetcher
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(FetchesCategory $fetcher, Request $request): JsonResponse
    {
        return CategoryCollection::make(
                $fetcher->fetchCollection($request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesCategory  $creator
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(CreatesCategory $creator, Request $request): JsonResponse
    {
        return CategoryResource::make(
                $creator->create($request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  FetchesCategory  $fetcher
     * @param  Request  $request
     * @param  integer  $id
     * @return JsonResponse
     */
    public function show(FetchesCategory $fetcher, Request $request, int $id): JsonResponse
    {
        return CategoryResource::make(
                $fetcher->fetch($id, $request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatesCategory  $updater
     * @param  Request  $request
     * @param  Category  $category
     * @return JsonResponse
     */
    public function update(UpdatesCategory $updater, Request $request, Category $category): JsonResponse
    {
        return CategoryResource::make(
                $updater->update($category, $request->all())
            )
            ->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeletesCategory  $deleter
     * @param  Category  $category
     * @return JsonResponse
     */
    public function destroy(DeletesCategory $deleter, Category $category): JsonResponse
    {
        $deleter->delete($category);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
