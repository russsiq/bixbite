<?php

// -----------------------------------------------------------------------------
//  Applies in index method admin controller. Use in Observer Class prop:
// -----------------------------------------------------------------------------
// protected $keysToForgetCache = [
//     'keyOfCache' => 'methodToRebuildCache',
// ];
// Run in Class: $this->cacheForgetByKeys($entity);

namespace BBCMS\Models\Traits;

use Illuminate\Database\Eloquent\Model as ParentModel;

trait CacheForgetByKeys
{
    /**
     * Clearing cache by keys.
     */
    protected function cacheForgetByKeys($entity = null)
    {
        $keys = $this->keysToForgetCache;

        if (is_array($keys) and array_diff_key($keys, array_keys(array_keys($keys)))) {
            foreach ($keys as $key => $method) {
                cache()->forget($key);

                if (is_subclass_of($entity, ParentModel::class)) {
                    $model = $entity->getModel();

                    if (method_exists($model, $method)) {
                        $model->$method();
                    }
                }
            }
        }
    }

    /**
     * Adding new keys to forget cache by keys.
     */
    protected function addToCacheKeys(array $keys)
    {
        $this->keysToForgetCache = array_merge($this->keysToForgetCache, $keys);
    }
}
