<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Article\MassUpdateArticleRequest;
use App\Http\Requests\Api\V1\Article\Store as StoreArticleRequest;
use App\Http\Requests\Api\V1\Article\Update as UpdateArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ArticlesController extends Controller
{
    use AuthorizesRequests;

    /**
     * Создать экземпляр контроллера.
     */
    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
        $this->middleware('can:massUpdate,'.Article::class)
            ->only('massUpdate');
    }

    /**
     * Отобразить список сущностей с дополнительной фильтрацией.
     *
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
                'attachments',
            ])
            ->advancedFilter();

        $collection = new ArticleCollection($articles);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Создать и сохранить сущность в хранилище.
     *
     * @param  StoreArticleRequest  $request
     * @return JsonResponse
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        $article = Article::create($request->validated());

        $resource = new ArticleResource($article->refresh());

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Отобразить сущность.
     *
     * @param  Article  $article
     * @return JsonResponse
     */
    public function show(Article $article): JsonResponse
    {
        $article->load([
            'categories',
            'attachments',
            'tags',
            'user',
        ]);

        $resource = new ArticleResource($article);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Обновить сущность в хранилище.
     *
     * @param  UpdateArticleRequest  $request
     * @param  Article  $article
     * @return JsonResponse
     */
    public function update(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        $article->update($request->validated());

        $article->load([
            'categories',
            'attachments',
            'tags',
            'user',
        ]);

        $resource = new ArticleResource($article);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Массово обновить сущности по массиву `id` в хранилище.
     *
     * @param  MassUpdateArticleRequest  $request
     * @return JsonResponse
     */
    public function massUpdate(MassUpdateArticleRequest $request): JsonResponse
    {
        // $this->authorize('massUpdate', Article::class);

        ['mass_action' => $attribute, 'articles' => $ids] = $request->validated();

        $query = Article::whereIn('id', $ids);

        switch ($attribute) {
            case 'published':
                $query->whereHas('categories')
                    ->update([
                        'state' => 2,
                    ]);
                break;
            case 'unpublished':
                $query->update([
                    'state' => 1,
                ]);
                break;
            case 'draft':
                $query->update([
                    'state' => 0,
                ]);
                break;
            case 'on_mainpage':
            case 'allow_com':
            case 'is_favorite':
            case 'is_catpinned':
                $article = $query->firstOrFail($attribute);
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
     *
     * @param  Article $article
     * @return JsonResponse
     */
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
