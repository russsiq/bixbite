<?php

namespace BBCMS\Models\Observers;

use BBCMS\Models\Category;
use BBCMS\Models\Traits\CacheForgetByKeys;

class CategoryObserver
{
    use CacheForgetByKeys;

    protected $keysToForgetCache = [
        'categories',
        'navigation_categories',
    ];

    public function deleting(Category $category)
    {
        $category->articles()->detach();
        $category->image()->get()->each->delete();
    }

    public function created(Category $category)
    {
        $this->cacheForgetByKeys();

        // Rebuild the cache.
        $category->getCachedCategories();
        $category->getCachedNavigationCategories();
    }

    public function updated(Category $category)
    {
        $this->cacheForgetByKeys();

        // Rebuild the cache.
        $category->getCachedCategories();
        $category->getCachedNavigationCategories();
    }

    public function saved(Category $category)
    {
        $this->cacheForgetByKeys();

        // Rebuild the cache.
        $category->getCachedCategories();
        $category->getCachedNavigationCategories();
    }

    public function restored(Category $category)
    {
        $this->cacheForgetByKeys();

        // Rebuild the cache.
        $category->getCachedCategories();
        $category->getCachedNavigationCategories();
    }

    public function deleted(Category $category)
    {
        $this->cacheForgetByKeys();

        // Rebuild the cache.
        $category->getCachedCategories();
        $category->getCachedNavigationCategories();
    }
}
