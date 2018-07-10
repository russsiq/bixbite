<?php

namespace BBCMS\Models\Traits;

/*
 * Applies in index method admin controller.
 * Use in model prop.
 * protected $keysToForgetCache = [];
 * example: Category::cacheForgetByKeys();
 */
trait CacheForgetByKeys
{
    public function cacheForgetByKeys()
    {
        foreach ($this->keysToForgetCache as $key) {
            cache()->forget($key);
        }
    }
}
