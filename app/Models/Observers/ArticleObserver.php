<?php

namespace App\Models\Observers;

// Сторонние зависимости.
use App\Models\Article;
use Illuminate\Http\Request;

/**
 * Наблюдатель модели `Article`.
 */
class ArticleObserver extends BaseObserver
{
    /**
     * [$request description]
     * @var Request
     */
    protected $request;

    /**
     * Массив ключей для очистки кэша.
     * @var array
     */
    protected $keysToForgetCache = [

    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Обработать событие `saved` модели.
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

        if ($article->state && $article->categories->isEmpty()) {
            $article->state = 0;
        }

        $article->tags()->sync(array_map(
            function (array $tag) use ($article) {
                return (int) $tag['id'];
            },
            $this->request->input('tags', [])
        ));

        // Always clear cache.
        $this->addToCacheKeys([
            'articles-single-'.$article->id => false,
        ]);

        $this->forgetCacheByKeys($article);
    }

    /**
     * Обработать событие `deleting` модели.
     * @param  Article  $article
     * @return void
     */
    public function deleting(Article $article): void
    {
        $article->tags()->detach();
        $article->categories()->detach();
        $article->comments()->get(['id'])->each->delete();

        // Always clear cache.
        $this->addToCacheKeys([
            'articles-single-'.$article->id => false,
        ]);
    }

    /**
     * Обработать событие `deleting` модели.
     * @param  Article  $article
     * @return void
     */
    public function deleted(Article $article): void
    {
        $this->forgetCacheByKeys($article);
    }
}
