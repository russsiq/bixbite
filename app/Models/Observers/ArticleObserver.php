<?php

namespace App\Models\Observers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleObserver extends BaseObserver
{
    /**
     * The request instance.
     */
    protected Request $request;

    /**
     * Create a new Observer.
     *
     * @param  Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the Article "saving" event.
     *
     * @param  Article  $article
     * @return void
     */
    public function saving(Article $article): void
    {
        if ($article->state && $article->categories->isEmpty()) {
            $article->state = 0;
        }

        // Always clear cache.
        $this->addToCacheKeys([
            'articles-single-'.$article->id => false,
        ]);
    }

    /**
     * Handle the Article "saved" event.
     *
     * @param  Article  $article
     * @return void
     */
    public function saved(Article $article): void
    {
        $article->categories()->sync(array_map(
            function (array $category) use ($article) {
                return (int) $category['id'];
            },
            $this->request->input('categories', [])
        ));

        $this->forgetCacheByKeys($article);
    }

    /**
     * Handle the Article "deleting" event.
     *
     * @param  Article  $article
     * @return void
     */
    public function deleting(Article $article): void
    {
        $article->categories()->detach();

        // Always clear cache.
        $this->addToCacheKeys([
            'articles-single-'.$article->id => false,
        ]);
    }

    /**
     * Handle the Article "deleted" event.
     *
     * @param  Article  $article
     * @return void
     */
    public function deleted(Article $article): void
    {
        $this->forgetCacheByKeys($article);
    }
}
