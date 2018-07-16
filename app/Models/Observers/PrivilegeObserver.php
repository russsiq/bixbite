<?php

namespace BBCMS\Models\Observers;

use BBCMS\Models\Privilege;
use BBCMS\Models\Traits\CacheForgetByKeys;

class PrivilegeObserver
{
    use CacheForgetByKeys;

    protected $keysToForgetCache = [
        'privileges' => 'privileges',
        'roles' => 'roles',
    ];

    public function tableUpdated(Privilege $privilege)
    {
        // Clear and rebuild the cache.
        $this->cacheForgetByKeys($privilege);
    }
}
