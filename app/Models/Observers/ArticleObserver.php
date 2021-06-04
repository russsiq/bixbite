<?php

namespace App\Models\Observers;

use App\Models\Article;

class ArticleObserver extends BaseObserver
{
    /**
     * Handle the Article "saving" event.
     *
     * @param  Article  $article
     * @return void
     */
    public function saving(Article $article): void
    {
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
