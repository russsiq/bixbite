<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Article;

use App\Http\Resources\ArticleResource;
use App\Http\Resources\ArticleCollection;

use App\Http\Requests\Api\V1\Article\Store as StoreArticleRequest;
use App\Http\Requests\Api\V1\Article\Update as UpdateArticleRequest;
use App\Http\Requests\Api\V1\Article\MassUpdate as MassUpdateArticleRequest;

use Illuminate\Http\JsonResponse;

class ArticlesController extends ApiController
{
    protected $advancedAbilityMap = [
        'massUpdate' => 'massUpdate',
    ];

    protected $advancedMethodsWithoutModels = [
        'massUpdate',
    ];

    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }

    /**
     * Отобразить список сущностей с дополнительной фильтрацией.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $articles = Article::with([
                'categories:categories.id,categories.title,categories.slug',
                'user:users.id,users.name',
            ])
            ->withCount([
                'comments',
                'files',
            ])
            ->advancedFilter();

        $collection = new ArticleCollection($articles);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Создать и сохранить сущность в хранилище.
     * @param  StoreArticleRequest  $request
     * @return JsonResponse
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        $article = Article::create($request->validated());

        $resource = new ArticleResource($article);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Отобразить сущность.
     * @param  Article  $article
     * @return JsonResponse
     */
    public function show(Article $article): JsonResponse
    {
        $article->load([
            'categories',
            'files',
            'tags',
            'user',
        ]);

        $resource = new ArticleResource($article);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     * @param  UpdateArticleRequest  $request
     * @param  Article  $article
     * @return JsonResponse
     */
    public function update(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        $article->update($request->validated());

        $article->load([
            'categories',
            'files',
            'tags',
            'user',
        ]);

        $resource = new ArticleResource($article);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Массово обновить сущности по массиву `id` в хранилище.
     * @param  MassUpdateArticleRequest  $request
     * @return JsonResponse
     */
    public function massUpdate(MassUpdateArticleRequest $request): JsonResponse
    {
        $ids = $request->articles;
        $attribute = $request->mass_action;
        $query = Article::whereIn('id', $ids);

        switch ($attribute) {
            case 'published':
                $query->whereHas('categories')
                    ->update([
                        'state' => $attribute,
                    ]);
                break;
            case 'unpublished':
            case 'draft':
                $query->update([
                    'state' => $attribute,
                ]);
                break;
            case 'on_mainpage':
            case 'allow_com':
            case 'is_favorite':
            case 'is_catpinned':
                $article = Article::whereId($ids[0])
                    ->firstOrFail([$attribute]);
                $query->update([
                    $attribute => ! $article->{$attribute},
                ]);
                break;
            case 'currdate':
                $query->timestamps = false;
                $query->update([
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => null,
                ]);
                $query->timestamps = true;
                break;
        }

        $collection = ArticleResource::collection(
            // No need to load relationships.
            Article::whereIn('id', $ids)->get()
        );

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Удалить сущность из хранилища.
     * @param  Article $article
     * @return JsonResponse
     */
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
