<?php

namespace App\Models\Observers;

use App\Models\Privilege;
use App\Models\Traits\CacheForgetByKeys;

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
