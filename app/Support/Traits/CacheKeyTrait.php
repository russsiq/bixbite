<?php

namespace BBCMS\Support\Traits;

// Сторонние зависимости.
use Illuminate\Support\Arr;

trait CacheKeyTrait
{
    public function cacheKey(array $params = [])
    {
        if (isset($this->updated_at)) {
            $params = Arr::add($params, 'timestamp', $this->updated_at->timestamp);
        }

        $params = Arr::add($params, 'app_theme', app_theme());
        $params = Arr::add($params, 'app_locale', app_locale());
        $params = Arr::add($params, 'role', auth()->guest() ? 'guest' : auth()->user()->role);

        $params = Arr::add($params, 'class', get_class($this));

        return md5(serialize($params));
    }
}
