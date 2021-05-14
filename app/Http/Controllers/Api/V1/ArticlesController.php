<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Actions\Article\CreatesArticle;
use App\Contracts\Actions\Article\DeletesArticle;
use App\Contracts\Actions\Article\FetchesArticle;
use App\Contracts\Actions\Article\MassUpdatesArticle;
use App\Contracts\Actions\Article\UpdatesArticle;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ArticlesController extends Controller
{
    use AuthorizesRequests;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param  FetchesArticle  $fetcher
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(FetchesArticle $fetcher, Request $request): JsonResponse
    {
        $collection = new ArticleCollection(
            $fetcher->fetchCollection($request->all())
        );

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatesArticle  $creator
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(CreatesArticle $creator, Request $request): JsonResponse
    {
        $resource = new ArticleResource(
            $creator->create($request->all())
        );

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  FetchesArticle  $fetcher
     * @param  Request  $request
     * @param  integer  $id
     * @return JsonResponse
     */
    public function show(FetchesArticle $fetcher, Request $request, int $id): JsonResponse
    {
        $resource = new ArticleResource(
            $fetcher->fetch($id, $request->all())
        );

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatesArticle  $updater
     * @param  Request  $request
     * @param  Article  $article
     * @return JsonResponse
     */
    public function update(UpdatesArticle $updater, Request $request, Article $article): JsonResponse
    {
        $resource = new ArticleResource(
            $updater->update($article, $request->all())
        );

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Update the specified resource collection in storage.
     *
     * @param  MassUpdatesArticle  $updater
     * @param  Request  $request
     * @return JsonResponse
     */
    public function massUpdate(MassUpdatesArticle $updater, Request $request): JsonResponse
    {
        $collection = ArticleResource::collection(
            $updater->massUpdate($request->all())
        );

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeletesArticle  $deleter
     * @param  Article  $article
     * @return JsonResponse
     */
    public function destroy(DeletesArticle $deleter, Article $article): JsonResponse
    {
        $deleter->delete($article);

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
