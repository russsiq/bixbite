<?php

namespace BBCMS\Support\Traits;

trait CacheKeyTrait
{
    public function cacheKey(array $params = [])
    {
        if (isset($this->updated_at)) {
            $params = array_add($params, 'timestamp', $this->updated_at->timestamp);
        }

        $params = array_add($params, 'app_theme', app_theme());
        $params = array_add($params, 'app_locale', app_locale());
        $params = array_add($params, 'role', auth()->guest() ? 'guest' : auth()->user()->role);

        $params = array_add($params, 'class', get_class($this));

        return md5(serialize($params));
    }
}
