<?php

namespace BBCMS\Models\Observers;

use BBCMS\Models\Article;
use BBCMS\Models\XField;
use BBCMS\Models\Traits\CacheForgetByKeys;

class ArticleObserver
{
    use CacheForgetByKeys;

    protected $keysToForgetCache = [
        //
    ];

    public function retrieved(Article $article)
    {
        $article->fillable(array_merge(
            $article->getFillable(),
            XField::fields($article->getTable())->pluck('name')->toArray()
        ));
    }

    public function saved(Article $article)
    {
        $dirty = $article->getDirty();

        // Set new or delete old article image.
        if (array_key_exists('image_id', $dirty)) {
            // Deleting always.
            $this->deleteImage($article);

            // Attaching.
            $this->attachImage($article);
        }

        // // Clear and rebuild the cache.
        // $this->addToCacheKeys([
        //     $article->id.'-tags' => 'tags',
        //     $article->id.'-categories' => 'categories',
        // ]);
        //
        // $this->cacheForgetByKeys($article);
    }

    public function updating(Article $article)
    {
        //
    }

    public function deleting(Article $article)
    {
        $article->tags()->detach();
        $article->categories()->detach();
        $article->comments()->get(['id'])->each->delete();
        $article->files()->get()->each->delete();

        // Deleting always.
        $this->deleteImage($article);

        // // Clear and rebuild the cache.
        // $this->addToCacheKeys([
        //     $article->id.'-tags' => 'tags',
        //     $article->id.'-categories' => 'categories',
        // ]);
        //
        // $this->cacheForgetByKeys($article);
    }

    protected function attachImage(Article $article)
    {
        $article->image()->update([
            'attachment_type' => $article->getMorphClass(),
            'attachment_id' => $article->id,
        ]);
    }

    protected function deleteImage(Article $article)
    {
        if (is_int($image_id = $article->getOriginal('image_id'))) {
            $article->files()->whereId($article->getOriginal('image_id'))
                ->get()->each->delete();
        }
    }
}
