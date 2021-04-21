<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Actions\Article\FetchingArticleCollection;
use App\Contracts\Actions\Article\FetchingArticleResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Article\IndexArticleRequest;
use App\Http\Requests\Api\V1\Article\StoreArticleRequest;
use App\Http\Requests\Api\V1\Article\UpdateArticleRequest;
use App\Http\Resources\V1\ArticleCollection;
use App\Http\Resources\V1\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ArticleController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, FetchingArticleCollection $fetcher)
    {
        $articles = $fetcher->fetchCollection($request->query());

        $collection = new ArticleCollection($articles);

        return $collection->response()
            ->setStatusCode(JsonResponse::HTTP_PARTIAL_CONTENT);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        /** @var \App\Models\Article $articleInstance */
        $article = $user->articles()
            ->create($request->validated());

        $resource = new ArticleResource(
            $article->refresh()
        );

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $article->load([
            'atachments',
            'categories',
            'tags',
            'user',
        ])->withCount([
            'comments',
        ]);

        $resource = new ArticleResource($article);

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateArticleRequest  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $article->update(
            $validated = $request->validated()
        );

        ! empty($validated['relationships']['categories']['data']) && $article->categories()->sync(
            new Collection($validated['relationships']['categories']['data'])
        );

        $resource = new ArticleResource(
            $article->fresh([
                'atachments',
                'categories',
                'tags',
                'user',
            ])
        );

        return $resource->response()
            ->setStatusCode(JsonResponse::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return response()
            ->json(null, JsonResponse::HTTP_NO_CONTENT);
    }
}
