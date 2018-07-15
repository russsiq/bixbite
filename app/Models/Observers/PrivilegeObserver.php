<?php

namespace BBCMS\Models\Observers;

use BBCMS\Models\Privilege;
use BBCMS\Models\Traits\CacheForgetByKeys;

class PrivilegeObserver
{
    use CacheForgetByKeys;

    protected $keysToForgetCache = [
        'privileges', 'roles',
    ];

    public function tableUpdated(Privilege $privilege)
    {
        $this->cacheForgetByKeys();

        // Rebuild the cache.
        $privilege->roles();
        $privilege->privileges();
    }
}
