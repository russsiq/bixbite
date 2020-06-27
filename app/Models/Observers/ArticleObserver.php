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
     * Обработать событие `retrieved` модели.
     * @param  Article  $article
     * @return void
     */
    public function retrieved(Article $article): void
    {
        $article->fillable(array_merge(
            $article->getFillable(),
            $article->x_fields->pluck('name')->toArray()
        ));
    }

    /**
     * Обработать событие `saved` модели.
     * @param  Article  $article
     * @return void
     */
    public function saved(Article $article): void
    {
        $article->categories()->sync(
            $this->request->input('categories', [])
        );

        if ($article->categories->isEmpty()) {
            $article->state = 'unpublished';
        }

        $article->tags()->sync(array_map(
            function (string $tag) use ($article) {
                return $article->tags()
                    ->getModel()
                    ->firstOrCreate([
                        'title' => $tag,
                    ])
                    ->id;
            },
            $this->request->input('tags', [])
        ));

        $dirty = $article->getDirty();

        // Set new or delete old article image.
        // By default if image set, then input with name `image_id` not visible.
        if (array_key_exists('image_id', $dirty)) {
            // Deleting always.
            $this->deleteImage($article);

            // Attaching.
            $this->attachImage($article);
        }

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
        $article->files()->get()->each->delete();

        // Deleting always.
        $this->deleteImage($article);

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

    /**
     * Прикрепить изображение к указанной записи.
     * @param  Article  $article
     * @return void
     */
    protected function attachImage(Article $article): void
    {
        if (is_int($image_id = $article->image_id)) {
            $article->files()
                ->getRelated()
                ->whereId($image_id)
                ->update([
                    'attachment_type' => $article->getMorphClass(),
                    'attachment_id' => $article->id,
                ]);
        }
    }

    /**
     * Открепить и удалить изображение от указанной записи.
     * @param  Article  $article
     * @return void
     */
    protected function deleteImage(Article $article): void
    {
        if (is_int($image_id = $article->getOriginal('image_id'))) {
            $article->files()
                ->whereId($image_id)
                ->get()
                ->each
                ->delete();
        }
    }
}
