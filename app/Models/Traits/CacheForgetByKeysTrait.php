<?php

namespace BBCMS\Models\Traits;

/*
 * Applies in index method admin controller
 * with in model
 * protected $keysToForgetCache = [];
 * example: Category::cacheForgetByKeys();
 */
trait CacheForgetByKeysTrait
{
    public function cacheForgetByKeys()
    {
        foreach ($this->keysToForgetCache as $key) {
            cache()->forget($key);
        }
    }
}
