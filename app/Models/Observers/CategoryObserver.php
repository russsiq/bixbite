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

    public function deleting(Category $category)
    {
        $category->articles()->detach();
        $category->image()->get()->each->delete();
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
}
