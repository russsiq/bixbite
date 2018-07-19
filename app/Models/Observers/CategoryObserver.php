<?php

namespace BBCMS\Models\Observers;

use BBCMS\Models\Category;
use BBCMS\Models\Traits\CacheForgetByKeys;

class CategoryObserver
{
    use CacheForgetByKeys;

    protected $keysToForgetCache = [
        'categories' => 'getCachedCategories',
        'navigation' => 'getCachedNavigationCategories',
    ];

    public function retrieved(Category $category)
    {
        $category->fillable(array_merge(
            $category->getFillable(),
            $category->x_fields->pluck('name')->toArray()
        ));
    }

    public function saved(Category $category)
    {
        $dirty = $category->getDirty();

        // Set new or delete old article image.
        if (array_key_exists('image_id', $dirty)) {
            // Deleting always.
            $this->deleteImage($category);

            // Attaching.
            $this->attachImage($category);
        }
    }

    public function deleting(Category $category)
    {
        $category->articles()->detach();
        $category->files()->get()->each->delete();
    }

    public function created(Category $category)
    {
        // Clear and rebuild the cache.
        $this->cacheForgetByKeys($category);
    }

    public function updated(Category $category)
    {
        // Clear and rebuild the cache.
        $this->cacheForgetByKeys($category);
    }

    public function restored(Category $category)
    {
        // Clear and rebuild the cache.
        $this->cacheForgetByKeys($category);
    }

    public function deleted(Category $category)
    {
        // Clear and rebuild the cache.
        $this->cacheForgetByKeys($category);
    }

    protected function attachImage(Category $category)
    {
        if (is_int($image_id = $category->image_id)) {
            $category->files()
                ->getRelated()
                ->whereId($image_id)
                ->update([
                    'attachment_type' => $category->getMorphClass(),
                    'attachment_id' => $category->id,
                ]);
        }
    }

    protected function deleteImage(Category $category)
    {
        if (is_int($image_id = $category->getOriginal('image_id'))) {
            $category->files()
                ->whereId($image_id)
                ->get()
                ->each
                ->delete();
        }
    }
}
